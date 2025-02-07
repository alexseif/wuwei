<?php

namespace App\Form;

use App\Entity\Milestones;
use App\Entity\Payments;
use App\Entity\Proposals;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('due_date', null, [
                'widget' => 'single_text',
            ])
            ->add('amount')
            ->add('status')
            ->add('proposal', EntityType::class, [
                'class' => Proposals::class,
                'choice_label' => 'id',
            ])
            ->add('milestone', EntityType::class, [
                'class' => Milestones::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payments::class,
        ]);
    }
}
