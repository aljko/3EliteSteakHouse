<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DateTime;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('personnes', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 20,
                    'value' => 1
                ]
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'choice',
                'empty_data' => '2020-03-10',
                'data' => new DateTime("now"),
                'format' => 'dd-MM-yyyy H-m',
                'years' => range(date('Y'), intval(date('Y'))+1),
                'months' => range(date('m'), intval(date('m'))+2),
                'days' => range(1, 31),
                'hours' => range(11, 22),
                'minutes' => range(0, 45, 15)
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
