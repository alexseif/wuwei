<?php

namespace App\Form;

use App\Entity\Schedule;
use App\Entity\TaskLists;
use App\Entity\Tasks;
use App\Entity\WorkLog;
use App\Repository\TaskListsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TasksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('task')
            ->add('taskList', EntityType::class, [
                'class' => TaskLists::class,
                'choice_label' => 'name',
                'query_builder' => function (TaskListsRepository $taskListsRepository) {
                    return $taskListsRepository->getActiveTaskLists();
                },
                'group_by' => 'account.name',
            ])
            ->add('est')
            ->add('duration')
            ->add('priority', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Low' => -1,
                    'Normal' => 0,
                    'Important' => 1,
                ],
                'expanded' => true,
                'label_attr' => ['class' => 'radio-inline'],
            ])
            ->add('urgency', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Normal' => 0,
                    'Urgent' => 1,
                ],
                'expanded' => true,
                'label_attr' => ['class' => 'radio-inline'],
            ])
            ->add('eta', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'date_format' => 'yyyy-MM-dd',
                'required' => false,
            ])
            ->add('completed')
            ->add('completedAt', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'date_format' => 'yyyy-MM-dd',
                'required' => false,
            ])
            ->add('order', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class,
        ]);
    }
}
