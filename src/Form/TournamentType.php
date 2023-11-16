<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Tournament;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom du tournoi",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('CashPrize', NumberType::class, [
                'label' => 'Quel est le cash prize du tournois ?',
                'attr' => [
                    'class' => 'form-control',
                ],
                'scale' => 2,
            ])
            ->add('location', TextType::class, [
                'label' => "lieu du tournoi (champ non obligatoire)",
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
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
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        
            //input to add/modify the editor's country in the data base
            ->add('game', EntityType::class, [
                'class' => Game::class,
                'label' => 'Jeu du tournoi',
                'choice_label' => ucwords('name'),
                // this query_builder allows me to choose in which order i would like the game to be in the select
                // en dql on recupere un object (instance de classe)
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('g')
                    ->orderBy('g.name', 'ASC');
                },
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
            'data_class' => Tournament::class,
        ]);
    }
}
