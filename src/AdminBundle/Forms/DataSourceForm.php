<?php

namespace AdminBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataSourceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', null, ['label' => 'dataSourceForm.label'])
            ->add('slug', null, ['label' => 'dataSourceForm.slug'])
            ->add('icon', null, ['label' => 'dataSourceForm.icon'])
            ->add('url', null, ['label' => 'dataSourceForm.url'])
            ->add('fields', null, ['label' => 'dataSourceForm.keys'])
        ;
    }

    public function getName()
    {
        return 'form_taxonomy_datasource_category';
    }
}
