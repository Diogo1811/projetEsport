<?php

namespace App\Form;

use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //input to add/modify the country's name on the data base
            ->add('name', TextType::class, [
                'label' => 'Nom du Pays',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to add/modify the country's flag on the data base
            ->add('flag', TextareaType::class, [
                'label' => 'Drapeau',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to validate the form and submit it
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
        ]);
    }
}
