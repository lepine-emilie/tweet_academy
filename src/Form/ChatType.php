<?php

namespace App\Form;

use App\Entity\ChatConversation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => " Chat title (optional)"
                ],
                'required' => false
            ])
            ->add('conv', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'Select your correspondant',
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChatConversation::class,
        ]);
    }
}
