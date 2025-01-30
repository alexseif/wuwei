<?php

namespace App\Form\Field;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class TagAutocompleteField extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'class' => Tag::class,
          'placeholder' => 'Choose a Tag',
            // 'choice_label' => 'name',

          'query_builder' => fn(TagRepository $tagRepository) => $tagRepository->createQueryBuilder('tag'),
          'required' => false,
          'multiple' => true,
            // 'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }

}
