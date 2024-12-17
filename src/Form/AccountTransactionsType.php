<?php

namespace App\Form;

use App\Entity\Accounts;
use App\Entity\AccountTransactions;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountTransactionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount')
            ->add('note')
            ->add('issuedAt', null, [
                'widget' => 'single_text',
            ])
            
            ->add('account', EntityType::class, [
                'class' => Accounts::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AccountTransactions::class,
        ]);
    }
}
