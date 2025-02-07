<?php

namespace App\Form;

use App\Entity\Milestones;
use App\Entity\Proposals;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MilestonesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('week')
            ->add('month')
            ->add('action_item')
            ->add('remarks')
            ->add('proposal', EntityType::class, [
                'class' => Proposals::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Milestones::class,
        ]);
    }
}
