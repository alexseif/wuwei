<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Client;
use App\Entity\Tag;
use App\Form\Field\EnabledField;
use App\Form\Field\StatusField;
use App\Form\Field\TagAutocompleteField;
use App\Repository\TagRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{

    public function buildForm(
      FormBuilderInterface $builder,
      array $options
    ): void {
        $builder
          ->add('name')
          ->add('Client', EntityType::class, [
            'class' => Client::class,
            'placeholder' => 'Account Client',
            'attr' => ['class' => 'select2'],
          ])
          ->add(
            'AccountTag', EntityType::class,
            [
              'class' => Tag::class,
              'query_builder' => function (TagRepository $tagRepository
              ): QueryBuilder {
                  return $tagRepository->createQueryBuilder('t')
                    ->leftJoin('t.tagType', 'tt')
                    ->where('tt.name = :tag_type')
                    ->setParameter('tag_type', 'Account');
              },
              'placeholder' => 'Account Tag',
              'attr' => ['class' => 'select2'],
            ]
          )
          ->add('status', StatusField::class)
          ->add('tags', TagAutocompleteField::class)
          ->add('enabled', EnabledField::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
          'data_class' => Account::class,
        ]);
    }

}
