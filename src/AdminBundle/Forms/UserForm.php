<?php

namespace AdminBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', 'choice', ['label' => 'userForm.roles', 'choices' => [
                'ROLE_USER' => 'Użytkownik', 
                'ROLE_ADMIN' => 'Administrator', 
                'ROLE_SUPER_ADMIN' => 'Główny administrator',
            ], 'multiple' => true])
        ;
    }    

    public function getName()
    {
        return 'form_user';
    }
}

