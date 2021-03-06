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
     * @Route("/api/price_parameters/", name="api.price_parameters")
     */
    public function priceParametersAction(Request $request)
    {
        $pageCollection = $this->getDoctrine()->getRepository('CmsBundle:PriceParameter')->findAll();

        $pages = [];
        foreach ($pageCollection as $page) {
            $pages[] = $page->toArray();
        }

        return new JsonResponse($pages, 200);
    }

    /**
     * @Route("/api/type_of_buildings/", name="api.type_of_buildings")
     */
    public function typeOfBuildingsAction(Request $request)
    {
        $pageCollection = $this->getDoctrine()->getRepository('CmsBundle:BuildingType')->findAll();

        $pages = [];
        foreach ($pageCollection as $page) {
            $pages[] = $page->toArray();
        }

        return new JsonResponse($pages, 200);
    }

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

    /**
     * @Route("/api/wording/", name="api.wording")
     */
    public function wordingAction(Request $request)
    {
        $wordingCollection = $this->getDoctrine()->getRepository('CmsBundle:Wording')->findAll();

        $wordings = [];
        foreach ($wordingCollection as $wording) {
            $wordings[] = $wording->toArray();
        }

        return new JsonResponse($wordings, 200);
    }
}
