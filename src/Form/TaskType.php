<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Task;
use App\Form\Field\TagAutocompleteField;
use App\Repository\TagRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            'attr' => [
              'tabindex' => -1,
            ],
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
          ->add('type', EntityType::class, [
            'class' => Tag::class,
            'query_builder' => function (TagRepository $tagRepository
            ): QueryBuilder {
                return $tagRepository->createQueryBuilder('t')
                  ->leftJoin('t.tagType', 'tt')
                  ->where('tt.name = :tag_type')
                  ->setParameter('tag_type', 'Task Type');
            },
            'placeholder' => 'Task Type',
            'attr' => ['class' => 'select2'],
          ])
          ->add('est')
          ->add('duration')
          ->add('eta', DateTimeType::class, [
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'yyyy-MM-dd',
            'required' => false,
            'attr' => [
              'class' => 'datepicker',
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
          ->add('completed', CheckboxType::class, [
            'label_attr' => ['class' => 'checkbox-switch'],
            'required' => false,
          ])
          ->add('tags', TagAutocompleteField::class)
          ->add('position', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
          'data_class' => Task::class,
        ]);
    }

}
