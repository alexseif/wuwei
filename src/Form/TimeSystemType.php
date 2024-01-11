<?php

namespace App\Form;

use App\Entity\TimeSystem;
use App\Form\Field\TagAutocompleteField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TimeSystemType extends AbstractType
{

    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(
      FormBuilderInterface $builder,
      array $options
    ): void {
        $ajaxUrl = $this->urlGenerator->generate(
          'app_tag_autocomplete'
        );
        $builder
          ->add('name')
          ->add('from_time')
          ->add('to_time')
          ->add('tags', TagAutocompleteField::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
          'data_class' => TimeSystem::class,
        ]);
    }

}
