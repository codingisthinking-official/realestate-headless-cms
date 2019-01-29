<?php

namespace AdminBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxonomyCategoryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', null, ['label' => 'taxonomyCategoryForm.label'])
            ->add('slug', null, ['label' => 'taxonomyCategoryForm.slug'])
            ->add('icon', null, ['label' => 'taxonomyCategoryForm.icon'])
        ;
    }    

    public function getName()
    {
        return 'form_taxonomy_category';
    }
}
