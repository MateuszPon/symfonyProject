<?php

namespace App\Form;

use App\Entity\Events;
use App\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchEventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',DateType::class)
            ->add('city',TextType::class)
            ->add('address',TextType::class)
//            ->add('quantity')
//            ->add('private_status')
        ;
    }
//
//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => Events::class,
//        ]);
//    }
}
