<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\Country;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use App\Form\SocialMediaAccountType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => "Nom de famille",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => "Prénom",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('nickname', TextType::class, [
                'label' => "Surnom",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre',
                'choices'  => [
                    'Homme' => "Homme",
                    'Femme' => "Femme",
                ],
                // display the choices like a check box
                'expanded' => true, 
                // Choosing if we allow multiple box cheked
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('biography', TextareaType::class, [
                'label' => "Biographie (champ non obligatoire)",
                'required' => false,
                'attr' => [
                        'class' => 'form-control'
                ]
            ])
            ->add('birthDate', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('earning', NumberType::class, [
                'label' => "Gains totaux du joueur (champ non obligatoire)",
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('socialMediaAccounts', CollectionType::class, [
                //collection in a form isn't always another form
                'entry_type' => SocialMediaAccountType::class,
                'prototype' => true,
                //allows add new element in entity Session (cascade_persist)
                //activate data prototype which is an html element that we will later be able to manipulate with js
                'allow_add' => true,
                'allow_delete' => true,
                // mandatory because Session doesn't have a setProgram, it's program who has setSession
                // program is the relationship owner
                // to avoid a mapping false we add by_reference
                'by_reference' => false,
                'label' => false,
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
            'data_class' => Player::class,
        ]);
    }
}
