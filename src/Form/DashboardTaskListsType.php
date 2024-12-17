<?php

namespace App\Form;

use App\Entity\DashboardTaskLists;
use App\Entity\TaskLists;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardTaskListsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('taskList', EntityType::class, [
                'class' => TaskLists::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DashboardTaskLists::class,
        ]);
    }
}
