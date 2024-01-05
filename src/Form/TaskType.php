<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{

    public function buildForm(
      FormBuilderInterface $builder,
      array $options
    ): void {
        $builder
          ->add('name')
          ->add('description', TextareaType::class, [
            'required' => false,
          ])
          ->add('priority', ChoiceType::class, [
            'choices' => [
              'Low' => -1,
              'Normal' => 0,
              'Important' => 1,
            ],
            'expanded' => true,
            'label_attr' => ['class' => 'radio-inline'],
          ])
          ->add('urgency', ChoiceType::class, [
            'choices' => [
              'Normal' => 0,
              'Urgent' => 1,
            ],
            'expanded' => true,
            'label_attr' => ['class' => 'radio-inline'],
          ])
          ->add('type')
          ->add('est')
          ->add('duration')
          ->add('eta', DateTimeType::class, [
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'yyyy-MM-dd',
            'required' => false,
            'attr' => [
              'class' => 'datepicker',
              'data-provide' => 'datepicker',
              'data-date-format' => 'yyyy-MM-dd',
            ],
          ])
          ->add('completed')
          ->add('completedAt', DateTimeType::class, [
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'yyyy-MM-dd',
            'required' => false,
            'attr' => [
              'class' => 'datepicker',
              'data-provide' => 'datepicker',
              'data-date-format' => 'yyyy-MM-dd',
            ],
          ])
          ->add('dueAt', DateTimeType::class, [
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'yyyy-MM-dd',
            'required' => false,
            'attr' => [
              'class' => 'datepicker',
              'data-provide' => 'datepicker',
              'data-date-format' => 'yyyy-MM-dd',
            ],
          ])
          ->add('tags', TagAutocompleteField::class, [
            'required' => false,
            'multiple' => true,
          ])
          ->add('position', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
          'data_class' => Task::class,
        ]);
    }

}
