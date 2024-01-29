<?php

namespace App\Form\Field;

use App\Entity\Traits\StatusableTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class StatusField extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'placeholder' => 'Choose a Status',
            // 'choice_label' => 'name',
          'choices' => [
            'Active' => 'active',
            'Archive' => 'archive',
          ],
          'expanded' => true,
          'label_attr' => ['class' => 'radio-inline'],
            // 'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

}
