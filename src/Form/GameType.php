<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Editor;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //input to add/modify the game's name on the data base
            ->add('name', TextType::class, [
                'label' => "Nom du jeu",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to add/modify the game's release date on the data base
            ->add('releaseDate', DateType::class, [
                'label' => 'Date de sortie',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to add/modify the game's link to purchese it on the data base
            ->add('linkToPurchase', TextareaType::class, [
                'label' => "Lien officiel pour l'achat du jeu",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to add/modify the game's editor on the data base
            ->add('editor', EntityType::class, [
                'class' => Editor::class,
                'label' => 'Editeur',
                'choice_label' => 'name',
                // this query_builder allows me to choose in which order i would like the editor to be in the select
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.name', 'ASC');
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
            'data_class' => Game::class,
        ]);
    }
}
