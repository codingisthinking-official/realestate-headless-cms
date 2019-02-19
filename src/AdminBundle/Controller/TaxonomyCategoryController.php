<?php

namespace AdminBundle\Controller;

use AdminBundle\Forms\TaxonomyCategoryForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\TaxonomyCategory;

class TaxonomyCategoryController extends Controller
{
    /**
     * @Route("/taxonomies/", name="admin.taxonomies.categories")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $this->get('admin.breadcrumb')->attach('List of Taxonomies', $this->generateUrl('admin.taxonomies.categories'));
        $taxonomyCategoryList = $this->getTaxonomyManager()->getTaxonomyCategories();
        $dataSourceList = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:DataSource')->findBy([], ['id' => 'desc']);

        return [
            'taxonomyCategoryList' => $taxonomyCategoryList,
            'dataSourceList' => $dataSourceList,
        ];
    }

    /**
     * @Route("/taxonomies/{id}/delete/", name="admin.taxonomies.delete_category")
     * @Template()
     */
    public function deleteAction(Request $request, TaxonomyCategory $taxonomy)
    {
        $this->get('doctrine.orm.entity_manager')->remove($taxonomy);
        $this->get('doctrine.orm.entity_manager')->flush();

        $this->get('session')->getFlashBag()->add('success', 'Removed.');

        return $this->redirectToRoute('admin.taxonomies.categories');
    }

    /**
     * @Route("/taxonomies/{id}/edit/", name="admin.taxonomies.edit_category")
     * @Template()
     */
    public function editAction(Request $request, TaxonomyCategory $taxonomy)
    {
        $this->get('admin.breadcrumb')->attach('List of Taxonomies', $this->generateUrl('admin.taxonomies.categories'));
        $this->get('admin.breadcrumb')->attach($taxonomy->getLabel(), $this->generateUrl('admin.taxonomies.edit_category', ['id' => $taxonomy->getId()]));

        $form = $this->createForm(new TaxonomyCategoryForm(), $taxonomy);
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
     * @Route("/taxonomies/new/", name="admin.taxonomies.new_category")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $this->get('admin.breadcrumb')->attach('List of Taxonomies', $this->generateUrl('admin.taxonomies.categories'));
        $this->get('admin.breadcrumb')->attach('New Taxonomy', $this->generateUrl('admin.taxonomies.new_category'));

        $form = $this->createForm(new TaxonomyCategoryForm(), new TaxonomyCategory());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $taxonomyCategory = $form->getData();

            $this->get('doctrine.orm.entity_manager')->persist($taxonomyCategory);
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
