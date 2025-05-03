<?php

namespace App\Form;

use App\Entity\Accounts;
use App\Entity\AccountTransactions;
use App\Repository\AccountsRepository;
use App\Repository\AccountTransactionsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountTransactionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('account', EntityType::class, [
                'class' => Accounts::class,
                'choice_label' => 'name',
                'group_by' => 'client.name',
                'placeholder' => 'Select an Account',
                'query_builder' => function (AccountsRepository $accountsRepository) {
                    return $accountsRepository->createQueryBuilder('a')
                        ->select('a, c')
                        ->leftJoin('a.client', 'c')
                        ->orderBy('c.name', 'ASC');
                },
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->add('amount', MoneyType::class, [
                'currency' => 'EGP',
            ])
            ->add('issuedAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('note')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AccountTransactions::class,
        ]);
    }
}
