<?php

namespace App\Form\Field;

use App\Entity\Traits\StatusableTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class EnabledField extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'label_attr' => ['class' => 'checkbox-switch'],
        ]);
    }

    public function getParent(): string
    {
        return CheckboxType::class;
    }

}
