<?php

namespace App\Form;

use App\Entity\Roster;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RosterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('playerRosters', CollectionType::class, [
                //collection in a form isn't allways another form
                'entry_type' => PlayerRosterType::class,
                'prototype' => true,
                //allows add new element in entity Session (cascade_persist)
                //activate data prototype which is an html element that we will later be able to manipulate with js
                'allow_add' => true,
                'allow_delete' => true,
                // mandatory because Session doesn't have a setProgram, it's program who has setSession
                // program is the relationship owner
                // to avoid a mapping false we add by_reference
                'by_reference' => false,
            ])
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
            'data_class' => Roster::class,
        ]);
    }
}
