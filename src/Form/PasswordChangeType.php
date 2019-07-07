<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class PasswordChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('current_password', PasswordType::class, [
                'constraints' => [
                    new UserPassword()
                ],
                'required' => true
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true
            ])
        ;
    }
}