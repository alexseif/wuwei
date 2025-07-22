<?php

namespace App\Form;

use App\Entity\Accounts;
use App\Entity\AccountServiceAssignment;
use App\Entity\ProductService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountServiceAssignmentType extends AbstractType
{

    public function buildForm(
      FormBuilderInterface $builder,
      array $options
    ): void {
        $builder
          ->add('account', EntityType::class, [
            'class' => Accounts::class,
            'choice_label' => 'name',
            'label' => 'Account',
            'placeholder' => 'Select an account',
            'required' => true,
          ])
          ->add('productService', EntityType::class, [
            'class' => ProductService::class,
            'choice_label' => 'name',
            'label' => 'Product/Service',
            'placeholder' => 'Select a product or service',
            'required' => true,
          ])
          ->add('price', null, [
            'label' => 'Price',
            'attr' => ['placeholder' => 'Enter price'],
            'required' => true,
          ])
          ->add('renewalDate', null, [
            'widget' => 'single_text',
            'label' => 'Renewal Date',
            'required' => true,
          ])
          ->add('recurrence_frequency', ChoiceType::class, [
            'label' => 'Renewal Frequency',
            'required' => false,
            'mapped' => false,
            'choices' => [
              'None' => '',
              'Monthly' => 'monthly',
              'Quarterly' => 'quarterly',
              'Yearly' => 'yearly',
            ],
            'expanded' => true,
            'placeholder' => 'Select renewal frequency',
            'attr' => [
              'class' => 'form-select',
              'help' => 'How often this service renews',
            ],
          ])
          ->add('isComplete', null, [
            'label' => 'Completed',
            'required' => false,
          ])
          ->add('note', null, [
            'label' => 'Notes',
            'required' => false,
            'attr' => ['placeholder' => 'Additional information about this assignment'],
          ]);

        // Add event listener to set the dropdown value based on existing RRULE when editing
        $builder->addEventListener(
          FormEvents::PRE_SET_DATA,
          function (FormEvent $event) {
              $assignment = $event->getData();
              $form = $event->getForm();

              if (!$assignment || null === $assignment->getId()) {
                  return;
              }

              $rrule = $assignment->getRrule();
              $frequency = '';

              if ($rrule) {
                  if (strpos($rrule, 'FREQ=MONTHLY;INTERVAL=1') !== false) {
                      $frequency = 'monthly';
                  } elseif (strpos(
                      $rrule,
                      'FREQ=MONTHLY;INTERVAL=3'
                    ) !== false) {
                      $frequency = 'quarterly';
                  } elseif (strpos(
                      $rrule,
                      'FREQ=YEARLY;INTERVAL=1'
                    ) !== false) {
                      $frequency = 'yearly';
                  }
              }

              $form->get('recurrence_frequency')->setData($frequency);
          }
        );

        // Add event listener to convert dropdown value to RRULE format when submitting
        $builder->addEventListener(
          FormEvents::POST_SUBMIT,
          function (FormEvent $event) {
              $form = $event->getForm();
              $assignment = $event->getData();

              $frequency = $form->get('recurrence_frequency')->getData();

              if ($frequency) {
                  switch ($frequency) {
                      case 'monthly':
                          $assignment->setRrule('FREQ=MONTHLY;INTERVAL=1');
                          break;
                      case 'quarterly':
                          $assignment->setRrule('FREQ=MONTHLY;INTERVAL=3');
                          break;
                      case 'yearly':
                          $assignment->setRrule('FREQ=YEARLY;INTERVAL=1');
                          break;
                      default:
                          $assignment->setRrule(null);
                  }
              } else {
                  $assignment->setRrule(null);
              }
          }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
          'data_class' => AccountServiceAssignment::class,
        ]);
    }

}
