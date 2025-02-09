<?php

namespace App\Form;

use App\Entity\Accounts;
use App\Entity\TaskLists;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskListsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Start' => 'start',
                    'Active' => 'active',
                    'Archive' => 'archive',
                ],
                'expanded' => true,
            ])
            ->add('account', EntityType::class, [
                'class' => Accounts::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaskLists::class,
        ]);
    }
}
