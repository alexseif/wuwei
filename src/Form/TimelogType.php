<?php
namespace App\Form;

use App\Entity\Timelog;
use App\Form\DataTransformer\MinutesToDateIntervalTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimelogType extends AbstractType
{
    private MinutesToDateIntervalTransformer $transformer;

    public function __construct(MinutesToDateIntervalTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start', null, [
                'widget' => 'single_text',
            ])
            ->add('duration', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Enter duration in minutes',
                    'min' => 0,
                    'step' => 1,
                ],
            ])
            ->add('log');

        // Apply the transformer to the duration field
        $builder->get('duration')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Timelog::class,
        ]);
    }
}
