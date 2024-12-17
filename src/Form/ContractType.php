<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Contract;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('hoursPerDay')
            ->add('startedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('isCompleted')
            ->add('completedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('billedOn')
            
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);
    }
}
