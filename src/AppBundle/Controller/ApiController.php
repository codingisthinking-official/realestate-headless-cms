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
    public function pagesAction(Request $request)
    {
        $pageCollection = $this->getDoctrine()->getRepository('CmsBundle:Page')->findAll();

        $pages = [];
        foreach ($pageCollection as $page) {
            $pages[] = $page->toArray();
        }

        return new JsonResponse($pages, 200);
    }

}
