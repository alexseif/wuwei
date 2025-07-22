<?php

namespace App\Form;

use App\Entity\ProductService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Name',
                'attr' => ['placeholder' => 'Enter product/service name']
            ])
            ->add('type', null, [
                'label' => 'Type',
                'attr' => ['placeholder' => 'e.g. hosting, domain, email']
            ])
            ->add('billingCycle', null, [
                'label' => 'Billing Cycle',
                'required' => false,
                'attr' => ['placeholder' => 'e.g. monthly, yearly']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductService::class,
        ]);
    }
}
