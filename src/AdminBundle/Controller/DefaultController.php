<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin.dashboard")
     * @Template()
     */
    public function indexAction()
    {
        $paginator = $this->get('knp_paginator');

        return [
            'taxonomies' => $this->getTaxonomyManager()->getTaxonomyCategories(),
            'paginator' => $paginator,
        ];
    }

    /**
     * @Template()
     */
    public function breadcrumbAction()
    {
        return [
            'breadcrumb' => $this->get('admin.breadcrumb')
        ];
    }

    protected function getTaxonomyManager()
    {
        return $this->get('manager.taxonomy');
    }
}
