<?php

namespace AdminBundle\Controller;

use AdminBundle\Forms\Fields\DropdownForm;
use AdminBundle\Forms\Fields\GalleryForm;
use AdminBundle\Forms\Fields\GeneratedForm;
use AdminBundle\Forms\Fields\ReferenceForm;
use AdminBundle\Forms\Fields\TextareaForm;
use AdminBundle\Forms\TaxonomyCategoryFieldsForm;
use AppBundle\Entity\TaxonomyCategory;
use AppBundle\Entity\TaxonomyCategoryField;
use AppBundle\Entity\TaxonomyItem;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaxonomyCategoryFieldsController extends Controller
{
    /**
     * @Route("/taxonomies/{id}/fields/", name="admin.taxonomies.fields_category")
     * @Template()
     */
    public function fieldsAction(Request $request, TaxonomyCategory $taxonomy)
    {
        $this->buildDefaultTaxonomyBreadcrumb($taxonomy);

        return [
            'taxonomy' => $taxonomy,
        ];
    }

    /**
     * @Route("/taxonomies/{id}/fields/{field}/edit/", name="admin.taxonomies.edit_field")
     * @Template()
     */
    public function editAction(Request $request, TaxonomyCategory $taxonomy, TaxonomyCategoryField $field)
    {
        $this->buildDefaultTaxonomyBreadcrumb($taxonomy);
        $this->get('admin.breadcrumb')->attach('Edit Custom Field', $this->generateUrl('admin.taxonomies.edit_field', [
            'id' => $taxonomy->getId(),
            'field' => $field->getId(),
        ]));

        if ($field->getType() == 1) {
            $field->setSettings((Boolean) $field->getSettings());
        }

        $form = $this->createForm($this->getFormInstanceByType($field->getType()), $field);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($field->getType() == 4 || $field->getType() == 8) {
                $field->setSettings($field->getSettings()->getSlug());
            }

            $this->get('doctrine.orm.entity_manager')->flush($field);

            $this->get('session')->getFlashBag()->add('success', 'Saved.');

            return $this->redirectToRoute('admin.taxonomies.fields_category', ['id' => $taxonomy->getId()]);
        }

        return [
            'taxonomy' => $taxonomy,
            'field' => $field,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/taxonomies/{id}/fields/new/{type}/", name="admin.taxonomies.new_field")
     * @Template()
     */
    public function newAction(Request $request, TaxonomyCategory $taxonomy, $type)
    {
        $this->buildDefaultTaxonomyBreadcrumb($taxonomy);
        $this->get('admin.breadcrumb')->attach('Add new Custom Field', $this->generateUrl('admin.taxonomies.new_field', [
            'id' => $taxonomy->getId(),
            'type' => $type,
        ]));

        $form = $this->createForm($this->getFormInstanceByType($type), new TaxonomyCategoryField());
        $form->get('type')->setData($type);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $taxonomyField = $form->getData();
            $taxonomyField->setTaxonomyCategory($taxonomy);

            if ($type == 4 || $type == 8) {
                $taxonomyField->setSettings($taxonomyField->getSettings()->getSlug());
            }

            $this->get('doctrine.orm.entity_manager')->persist($taxonomyField);
            $this->get('doctrine.orm.entity_manager')->flush();

            $this->get('session')->getFlashBag()->add('success', 'Saved.');

            return $this->redirectToRoute('admin.taxonomies.fields_category', ['id' => $taxonomy->getId()]);
        } 

        return [
            'taxonomy' => $taxonomy,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/taxonomies/fields/{item}/{path}/{field}/", name="admin.taxonomies.delete_item_field")
     */
    public function deleteFieldContentAction(Request $request, $item, $path, $field)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        /** @var TaxonomyCategory $taxonomyCategory */
        $taxonomyCategory = $em->getRepository('AppBundle:TaxonomyCategory')->findOneBy(['id' => $path]);

        $entityClass = $taxonomyCategory->getSlug();

        $item = $em->getRepository($entityClass)->findOneBy([
            'id' => $item,
        ]);

        $setter = 'set' . $field;

        $item->{$setter}(null);
        $em->flush($item);

        $this->get('session')->getFlashBag()->add('success', 'Removed.');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/taxonomies/{id}/fields/{field}/delete/", name="admin.taxonomies.delete_field")
     * @Template()
     */
    public function deleteAction(Request $request, TaxonomyCategory $taxonomy, TaxonomyCategoryField $field)
    {
        $this->get('doctrine.orm.entity_manager')->remove($field);
        $this->get('doctrine.orm.entity_manager')->flush();

        $this->get('session')->getFlashBag()->add('success', 'Saved.');

        return $this->redirectToRoute('admin.taxonomies.fields_category', ['id' => $taxonomy->getId()]);
    }

    protected function buildDefaultTaxonomyBreadcrumb(TaxonomyCategory $taxonomy)
    {
        $this->get('admin.breadcrumb')->attach('List of Taxonomies', $this->generateUrl('admin.taxonomies.categories'));
        $this->get('admin.breadcrumb')->attach(
            sprintf('Taksonomia: %s', $taxonomy->getLabel()), 
            $this->generateUrl('admin.taxonomies.edit_category', ['id' => $taxonomy->getId()])
        );
        $this->get('admin.breadcrumb')->attach('Addational Custom Fields', $this->generateUrl('admin.taxonomies.fields_category', [
            'id' => $taxonomy->getId()
        ]));
    }

    protected function getFormInstanceByType($type)
    {
        switch ($type) {
            case 1:
                return new TextareaForm();
            case 3:
                return new GalleryForm();
            case 4:
                return new ReferenceForm();
            case 5:
                return new DropDownForm();
            case 8:
                return new ReferenceForm();
            default:
                return new TaxonomyCategoryFieldsForm();
        }
    }
}
