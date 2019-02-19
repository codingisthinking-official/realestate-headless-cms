<?php

namespace AdminBundle\Controller;

use AdminBundle\Forms\DataSourceForm;
use AppBundle\Entity\DataSource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DataSourceController extends Controller
{
    /**
     * @Route("/datasources/{id}/delete/", name="admin.datasources.delete")
     * @Template()
     */
    public function deleteAction(Request $request, DataSource $taxonomy)
    {
        $this->get('doctrine.orm.entity_manager')->remove($taxonomy);
        $this->get('doctrine.orm.entity_manager')->flush();

        $this->get('session')->getFlashBag()->add('success', 'Removed.');

        return $this->redirectToRoute('admin.taxonomies.categories');
    }

    /**
     * @Route("/datasources/{id}/edit/", name="admin.datasources.edit")
     * @Template()
     */
    public function editAction(Request $request, DataSource $taxonomy)
    {
        $this->get('admin.breadcrumb')->attach('List of DataSources', $this->generateUrl('admin.taxonomies.categories'));
        $this->get('admin.breadcrumb')->attach($taxonomy->getLabel(), $this->generateUrl('admin.taxonomies.edit_category', ['id' => $taxonomy->getId()]));

        $form = $this->createForm(new DataSourceForm(), $taxonomy);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('doctrine.orm.entity_manager')->flush($taxonomy);

            $this->get('session')->getFlashBag()->add('success', 'Saved.');

            return $this->redirectToRoute('admin.taxonomies.categories');
        }

        return [
            'form' => $form->createView(),
            'taxonomy' => $taxonomy,
        ];
    }

    /**
     * @Route("/datasources/new/", name="admin.datasources.new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $this->get('admin.breadcrumb')->attach('List of DataSources', $this->generateUrl('admin.taxonomies.categories'));
        $this->get('admin.breadcrumb')->attach('New DataSource', $this->generateUrl('admin.datasources.new'));

        $form = $this->createForm(new DataSourceForm(), new DataSource());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $dataSource = $form->getData();

            $this->get('doctrine.orm.entity_manager')->persist($dataSource);
            $this->get('doctrine.orm.entity_manager')->flush();

            $this->get('session')->getFlashBag()->add('success', 'Saved');

            return $this->redirectToRoute('admin.taxonomies.categories');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    protected function getTaxonomyManager()
    {
        return $this->get('manager.taxonomy');
    }
}
