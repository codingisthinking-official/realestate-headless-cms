<?php

namespace AdminBundle\Forms\Fields;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferenceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', null, ['label' => 'taxonomyCategoryFieldsForm.label'])
            ->add('type', 'hidden', ['label' => 'taxonomyCategoryFieldsForm.type'])
            ->add('slug', null, ['label' => 'taxonomyCategoryFieldsForm.slug'])
            ->add('settings', 'entity', ['label' => 'taxonomyCategoryFieldsForm.settings.reference', 'required' => true, 'class' => 'AppBundle:TaxonomyCategory', 'choice_label' => 'label'])
            ->add('visible', null, ['label' => 'taxonomyCategoryFieldsForm.visible'])
        ;
    }    

    public function getName()
    {
        return 'form_taxonomy_category_dropdown';
    }
}

