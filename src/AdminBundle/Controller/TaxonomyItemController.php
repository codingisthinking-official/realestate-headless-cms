<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Services\TaxonomyManager;
use AdminBundle\Forms\TaxonomyItemSearchForm;
use AppBundle\Entity\TaxonomyItem;
use AppBundle\Entity\TaxonomyCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaxonomyItemController extends Controller
{
    /**
     * @Route("/taxonomy/{id}/", name="admin.taxonomy")
     * @Template()
     */
    public function indexAction(Request $request, TaxonomyCategory $taxonomy)
    {
        $taxonomySearchForm = new TaxonomyItemSearchForm();
        $taxonomySearchForm->setTaxonomyCategory($taxonomy);

        $searchForm = $this->createForm($taxonomySearchForm);
        $searchForm->handleRequest($request);

        $this->get('admin.breadcrumb')->attach(
            $taxonomy->getLabel(), $this->generateUrl('admin.taxonomy', ['id' => $taxonomy->getId()])
        );

        $paginator = $this->get('knp_paginator');

        $taxonomyItemList = $this
            ->getDoctrine()
            ->getRepository($taxonomy->getSlug());

        if ($request->isMethod('POST') && $searchForm->isValid()) {
            $data = $searchForm->getData();

            $taxonomyItemList = $taxonomyItemList
                ->createQueryBuilder('i')
                ->andWhere('i.title LIKE :query')
                ->setParameter('query', '%' . $data['title'] . '%')
                ->orderBy('i.id', 'desc');
        } else {
            if ($taxonomy->getId() == 8) {
                $taxonomyItemList = $taxonomyItemList
                    ->createQueryBuilder('i')
                    ->leftJoin('i.page', 'p')
                    ->orderBy('p.title', 'asc');
            } else if ($taxonomy->getId() == 21) {
                $taxonomyItemList = $taxonomyItemList
                    ->createQueryBuilder('i')
                    ->orderBy('i.title', 'asc');
            } else {
                $taxonomyItemList = $taxonomyItemList->findBy([], ['id' => 'desc']);
            }
        }

        $pager = $paginator->paginate(
            $taxonomyItemList,
            $request->query->getInt('page', 1),
            30
        );

        return [
            'pager' => $pager,
            'taxonomy' => $taxonomy,
            'searchForm' => $searchForm->createView(),
        ];
    }

    /**
     * @Route("/taxonomy/{taxonomy}/{id}/edit/", name="admin.edit_taxonomy_item")
     * @Template()
     */
    public function editAction(Request $request, TaxonomyCategory $taxonomy, $id)
    {
        $class = $taxonomy->getSlug();
        $em = $this->getDoctrine();

        $item = $em->getRepository($class)->find($id);

        $this->get('admin.breadcrumb')->attach(
            $taxonomy->getLabel(), $this->generateUrl('admin.taxonomy', ['id' => $taxonomy->getId()])
        );
        $this->get('admin.breadcrumb')->attach(
            sprintf('Edit: %s', $item->getTitle()),
            $this->generateUrl('admin.edit_taxonomy_item', ['taxonomy' => $taxonomy->getId(), 'id' => $item->getId()])
        );

        $em = $this->get('doctrine.orm.entity_manager');
        $taxonomyItemForm = $this->get('admin.taxonomy_item.form'); 
        $taxonomyItemForm->setTaxonomyCategory($taxonomy);

        $data = [];
        $data['title'] = $item->getTitle();

        foreach ($taxonomy->getFields() as $field) {
            if ($field->getType() == TaxonomyManager::TYPE_FILE || $field->getType() == TaxonomyManager::TYPE_IMAGE) {
                $data[$field->getSlug()] = null;
                continue;
            }

            $setter = 'set' . $field->getSlug();
            $getter = 'get' . $field->getSlug();

            $data[$field->getSlug()] = $item->{$getter}();
        }

        $form = $this->createForm($taxonomyItemForm);
        $form->setData($data);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $item->setTitle($data['title']);

                foreach ($taxonomy->getFields() as $field) {
                    $setter = 'set' . $field->getSlug();
                    $getter = 'get' . $field->getSlug();

                    if ($field->getType() == TaxonomyManager::TYPE_FILE || $field->getType() == TaxonomyManager::TYPE_IMAGE) {
                        if ($data[$field->getSlug()] == null) {
                            continue;
                        }
                    }

                    $item->{$setter}($data[$field->getSlug()]);
                }

                $this->getTaxonomyManager()->uploadFiles($taxonomy, $item);

                $em->flush($item);

                $this->get('session')->getFlashBag()->add('success', 'Saved.');

                if ('save-and-continue' == $request->request->get('action')) {
                    return $this->redirect($request->getUri());
                }

                return $this->redirectToRoute('admin.taxonomy', ['id' => $taxonomy->getId()]);
            }
        }

        return [
            'taxonomy' => $taxonomy,
            'taxonomyItem' => $item,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/taxonomy/{taxonomy}/{id}/delete/", name="admin.delete_taxonomy_item")
     * @Template()
     */
    public function deleteAction(Request $request, TaxonomyCategory $taxonomy, $id)
    {
        $class = $taxonomy->getSlug();
        $em = $this->getDoctrine();

        $item = $em->getRepository($class)->find($id);

        $this->get('doctrine.orm.entity_manager')->remove($item);
        $this->get('doctrine.orm.entity_manager')->flush();

        $this->get('session')->getFlashBag()->add('success', 'Removed');

        return $this->redirectToRoute('admin.taxonomy', ['id' => $taxonomy->getId()]);
    }

    /**
     * @Route("/taxonomy/{id}/new/", name="admin.new_taxonomy_item")
     * @Template()
     */
    public function newAction(Request $request, TaxonomyCategory $taxonomy)
    {
        $this->get('admin.breadcrumb')->attach(
            $taxonomy->getLabel(), $this->generateUrl('admin.taxonomy', ['id' => $taxonomy->getSlug()])
        );
        $this->get('admin.breadcrumb')->attach(
            'Add New', $this->generateUrl('admin.new_taxonomy_item', ['id' => $taxonomy->getSlug()])
        );

        $em = $this->get('doctrine.orm.entity_manager');
        $taxonomyItemForm = $this->get('admin.taxonomy_item.form'); 
        $taxonomyItemForm->setTaxonomyCategory($taxonomy);

        $tmp = $taxonomy->getSlug();
        $repository = $this->getDoctrine()->getRepository($tmp);

        $class = $repository->getClassName();

        $form = $this->createForm($taxonomyItemForm, new $class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getTaxonomyManager()->uploadFiles($taxonomy, $form->getData());

            $em->persist($form->getData());
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Added.');

            return $this->redirectToRoute('admin.taxonomy', ['id' => $taxonomy->getId()]);
        }

        return [
            'taxonomy' => $taxonomy,
            'taxonomyItem' => null,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Template()
     */
    public function sidebarListAction(Request $request, array $display = [], $currentItem)
    {
        return [
            'taxonomies' =>  $this->getTaxonomyManager()->getTaxonomyCategories(),
            'currentItem' => $currentItem,
            'display' => $display,
        ];
    }

    protected function getTaxonomyManager()
    {
        return $this->get('manager.taxonomy');
    }
}
