<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('display_name', TextType::class, [
                'attr' => [
                    'placeholder' => "Full name",
                    'class' => "form-input"
                ],
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => 24
                    ])
                ]
            ])
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => "Username",
                    'class' => "form-input"
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 16
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-z0-9_]+$/'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => "Email",
                    'class' => "form-input"
                ],
                'constraints' => [
                    new Email()
                ]
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'placeholder' => "Password",
                    'class' => "form-input"
                ]
            ])
            ->add('sign_up', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
