<?php

namespace App\Form;

use App\Entity\Client;
use App\Form\Field\TagAutocompleteField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{

    public function buildForm(
      FormBuilderInterface $builder,
      array $options
    ): void {
        $builder
          ->add('name')
          ->add('tags', TagAutocompleteField::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
          'data_class' => Client::class,
        ]);
    }

}
