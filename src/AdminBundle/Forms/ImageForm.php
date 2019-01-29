<?php

namespace AdminBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class ImageForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', 'file', ['label' => 'imageForm.image', 'constraints' => [new Constraints\Image()]])
            ->add('submit', 'submit', ['label' => 'imageForm.submit'])
        ;
    }    

    public function getName()
    {
        return 'form_image_gallery';
    }
}


