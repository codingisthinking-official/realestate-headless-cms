<?php

namespace AppBundle\Controller;

use AppBundle\ValueObject\NewContact;
use CmsBundle\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends Controller
{
    /**
     * @Route("/api/pages/", name="api.pages")
     */
    public function indexAction(Request $request)
    {
        return new JsonResponse(array_map(function($page) {
            return $page->toJson();
        }, $this->getPageRepository()->findAll()), 200);
    }

    /**
     * @Route("/api/contact/", name="api.new_contact")
     * @Method({"POST", "HEAD"})
     */
    public function newContactAction(Request $request)
    {
        $contactRequestData = json_decode($request->getContent(), true);

        $contact = new NewContact($contactRequestData['email'], $contactRequestData['text']);
        /** @var ValidatorInterface $validator */
        $validator = $this->get('validator');

        $errors = $validator->validate($contact);

        if (count($errors)) {
            /** @var $error ConstraintViolation */
            return new JsonResponse(['errors' => array_map(function($error) {
               return $error->getMessage();
            }, $this->returnErrorsAsArray($errors))], 400);
        }

        $contactDomain = new Contact();
        $contactDomain->setTitle($contact->getEmail());
        $contactDomain->setText($contact->getText());

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($contactDomain);
        $entityManager->flush();

        return new JsonResponse(['result' => 'ok'], 200);
    }

    protected function returnErrorsAsArray(ConstraintViolationListInterface $errors)
    {
        $errorList = [];

        foreach ($errors as $error) {
            $errorList[] = $error;
        }

        return $errorList;
    }

    protected function getPageRepository()
    {
        return $this->getDoctrine()->getRepository('CmsBundle:Page');
    }

    protected function getContactRepository()
    {
        return $this->getDoctrine()->getRepository('CmsBundle:Contact');
    }
}
