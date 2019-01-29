<?php

namespace AdminBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class ChangeEmailForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', ['label' => 'user.email', 'constraints' => [new Constraints\Email()]])
            ->add('submit', 'submit', ['label' => 'user.submit'])
        ;
    }

    public function getName()
    {
        return 'change_email_form';
    }
}


