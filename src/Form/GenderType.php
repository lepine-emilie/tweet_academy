<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenderType extends AbstractType
{
    const GENDER_MALE = 0;
    const GENDER_FEMALE = 1;
    const GENDER_OTHER = 2;

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [
                '' => null,
                'Male' => self::GENDER_MALE,
                'Female' => self::GENDER_FEMALE,
                'Other' => self::GENDER_OTHER,
            ],
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
