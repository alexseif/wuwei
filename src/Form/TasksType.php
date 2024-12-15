<?php

namespace App\Form;

use App\Entity\Schedule;
use App\Entity\TaskLists;
use App\Entity\Tasks;
use App\Entity\WorkLog;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TasksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('task')
            ->add('order')
            ->add('priority')
            ->add('urgency')
            ->add('duration')
            ->add('est')
            ->add('eta', null, [
                'widget' => 'single_text',
            ])
            ->add('completed')
            ->add('completedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('workLoggable')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('taskList', EntityType::class, [
                'class' => TaskLists::class,
                'choice_label' => 'id',
            ])
            ->add('schedule', EntityType::class, [
                'class' => Schedule::class,
                'choice_label' => 'id',
            ])
            ->add('workLog', EntityType::class, [
                'class' => WorkLog::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class,
        ]);
    }
}
