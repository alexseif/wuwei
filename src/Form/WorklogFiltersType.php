<?php

namespace App\Form;

use App\Entity\Accounts;
use App\Entity\Client;
use App\Entity\TaskLists;
use App\Entity\Tasks;
use App\Entity\WorkLog;
use App\Repository\AccountsRepository;
use App\Repository\TaskListsRepository;
use App\Repository\TasksRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorklogFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('task', EntityType::class, [
                'class' => Tasks::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Select a Task',
                'attr' => [
                    'class' => 'select2',
                ],
                'multiple' => true,
                'group_by' => function ($choice, $key, $value) {
                    return $choice->getTaskList();
                },
                'query_builder' => function (TasksRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->join('t.taskList', 'tl')
                        ->addSelect('tl')
                    ;
                },
            ])
            ->add('taskList', EntityType::class, [
                'class' => TaskLists::class,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'placeholder' => 'Select a Task List',
                'attr' => [
                    'class' => 'select2',
                ],
                'group_by' => function ($choice, $key, $value) {
                    return $choice->getAccount();
                },
                'query_builder' => function (TaskListsRepository $er) {
                    return $er->createQueryBuilder('tl')
                        ->join('tl.account', 'a')
                        ->addSelect('a')
                    ;
                },
            ])
            ->add('account', EntityType::class, [
                'class' => Accounts::class,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'placeholder' => 'Select an Account',
                'attr' => [
                    'class' => 'select2',
                ],
                'group_by' => function ($choice, $key, $value) {
                    return $choice->getClient();
                },
                'query_builder' => function (AccountsRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->join('a.client', 'c')
                        ->addSelect('c')
                    ;
                },
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'placeholder' => 'Select a Client',
                'attr' => [
                    'class' => 'select2',
                ],

            ])
            ->add('Filter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => WorkLog::class,
            'method' => 'GET'
        ]);
    }
}
