<?php

namespace AdminBundle\Forms\Fields;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextareaForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', null, ['label' => 'taxonomyCategoryFieldsForm.label'])
            ->add('type', 'hidden', ['label' => 'taxonomyCategoryFieldsForm.type'])
            ->add('slug', null, ['label' => 'taxonomyCategoryFieldsForm.slug'])
            ->add('settings', 'checkbox', ['label' => 'taxonomyCategoryFieldsForm.settings.tinymce', 'required' => false])
        ;
    }    

    public function getName()
    {
        return 'form_taxonomy_textarea_dropdown';
    }
}


