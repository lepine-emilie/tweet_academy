<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class UserPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'What\'s quacking?',
                    'id' => 'AreaPostMkr'
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Your message cannot be empty'
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 140,
                        'minMessage' => 'Your message cannot be empty',
                        'maxMessage' => 'Your quack can not exceed 140 characters'
                    ])
                ]
            ])
            ->add('media_url', FileType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'inputUpload'
                ],
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' =>
                            "This picture's format is not supported"
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
