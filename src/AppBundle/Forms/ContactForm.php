<?php

namespace AppBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class ContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', ['label' => 'app.contactForm.name', 'constraints' => [new Constraints\NotBlank()]])
            ->add('email', 'email', ['label' => 'app.contactForm.email', 'constraints' => [new Constraints\NotBlank()]])
            ->add('body', 'textarea', ['label' => 'app.contactForm.body', 'constraints' => [new Constraints\NotBlank()]])
            ->add('submit', 'submit', ['label' => 'app.contactForm.submit'])
        ;
    }    

    public function getName()
    {
        return 'contact_form';
    }
}


