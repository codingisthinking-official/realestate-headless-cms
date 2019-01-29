<?php

namespace AdminBundle\Forms;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use AppBundle\Entity\TaxonomyCategory;
use AppBundle\Entity\TaxonomyCategoryField;
use AppBundle\Services\TaxonomyManager;

class TaxonomyItemForm extends AbstractType
{
    protected $taxonomyCategory;
    protected $taxonomyManager;
    protected $entityManager;

    public function __construct(TaxonomyManager $taxonomyManager, EntityManager $entityManager)
    {
        $this->taxonomyManager = $taxonomyManager;
        $this->entityManager = $entityManager;
    }

    public function setTaxonomyCategory(TaxonomyCategory $taxonomyCategory)
    {
        $this->taxonomyCategory = $taxonomyCategory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'taxonomyItemForm.title',
            ])
        ;

        foreach ($this->taxonomyCategory->getFields() as $customField) {
            switch ($customField->getType()) {
                case 0: // text, input
                    $this->appendInputText($builder, $customField);

                    break;
                case 1: // textarea
                    $this->appendTextarea($builder, $customField);

                    break;
                case 2: // single image
                    $this->appendImage($builder, $customField);

                    break;
                case 4: // textarea
                    $this->appendReference($builder, $customField);

                    break;
                case 5: // dropdown
                    $this->appendDropdown($builder, $customField);

                    break;
                case 6: // yes/no
                    $this->appendYesNo($builder, $customField);

                    break;
                case 7: // attachment
                    $this->appendAttachment($builder, $customField);

                    break;
                case 8: // list of references
                    $this->appendListOfReferences($builder, $customField);

                    break;
                case 9: // key value table storage
                    $this->appendKeyValueTable($builder, $customField);
            }
        }
    }    

    protected function appendReference(FormBuilderInterface $builder, TaxonomyCategoryField $field)
    {
        $choices = [];

        $referenceRepository = $this->entityManager->getRepository($field->getSettings());

        foreach ($referenceRepository->findAll() as $item) {
            $choices[$item->getId()] = (String) $item;
        }

        $builder
            ->add($field->getSlug(), 'entity', [
                'class' => $field->getSettings(),
                'required' => false,
            ])
        ;
    }

    protected function appendKeyValueTable(FormBuilderInterface $builder, TaxonomyCategoryField $field)
    {
        $builder
            ->add($field->getSlug(), 'textarea', [
                'label' => $field->getLabel(),
            ])
        ;
    }

    protected function appendListOfReferences(FormBuilderInterface $builder, TaxonomyCategoryField $field)
    {
        $choices = [];

        $referenceRepository = $this->entityManager->getRepository($field->getSettings());

        foreach ($referenceRepository->findAll() as $item) {
            $choices[$item->getId()] = (String) $item;
        }

        $builder
            ->add($field->getSlug(), 'entity', [
                'class' => $field->getSettings(),
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    protected function appendYesNo(FormBuilderInterface $builder, TaxonomyCategoryField $field)
    {
        $builder
            ->add($field->getSlug(), 'checkbox', [
                'label' => $field->getLabel(),
                'required' => false,
            ])
        ;
    }

    protected function appendDropdown(FormBuilderInterface $builder, TaxonomyCategoryField $field)
    {
        $choices = array_map(function($item) {
            return trim($item);
        }, explode(',', trim($field->getSettings())));

        $options = [];
        foreach ($choices as $choice) {
            $options[$choice] = $choice;
        }

        $builder
            ->add($field->getSlug(), 'choice', [
                'label' => $field->getLabel(),
                'choices' => $options,
            ])
        ;
    }

    protected function appendTextarea(FormBuilderInterface $builder, TaxonomyCategoryField $field)
    {
        $builder
            ->add($field->getSlug(), 'textarea', [
                'label' => $field->getLabel(),
            ])
        ;
    }

    protected function appendAttachment(FormBuilderInterface $builder, TaxonomyCategoryField $field)
    {
        $builder
            ->add($field->getSlug(), 'file', [
                'label' => $field->getLabel(),
                'required' => false,
            ])
        ;
    }


    protected function appendImage(FormBuilderInterface $builder, TaxonomyCategoryField $field)
    {
        $builder
            ->add($field->getSlug(), 'file', [
                'label' => $field->getLabel(),
                'required' => false,
                'constraints' => [
                    new Constraints\Image(),
                ],
            ])
        ;
    }

    protected function appendInputText(FormBuilderInterface $builder, TaxonomyCategoryField $field)
    {
        $builder
            ->add($field->getSlug(), 'text', [
                'label' => $field->getLabel(),
            ])
        ;
    }

    public function getName()
    {
        return 'form_item';
    }
}

