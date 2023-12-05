<?php

namespace App\Form;

use App\Entity\Game;
use Doctrine\ORM\QueryBuilder;
use PhpParser\Parser\Multiple;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Types\DecimalType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Tournament's name input
            ->add('name', TextType::class, [
                'label' => 'Nom du tournoi',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's type select
            ->add('tournament_type', ChoiceType::class, [
                'label' => 'Type du tournoi',
                'choices' => [
                    'Elimination Simple' => 'single elimination',
                    'Elimination Double' => 'double elimination',
                    'Round-robin' => ' round robin',
                    'Système Suisse' => 'swiss',
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's description input
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament open sign up radio
            ->add('open_signup', ChoiceType::class, [
                'label' => 'Inscriptions ouvertes ?',
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            // tournament match for the third place radio only if single elimination is choosen
            ->add('hold_third_place_match', ChoiceType::class, [
                'label' => 'Petite finale ?',
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            // tournament's points per match win only if swiss type is choosen
            ->add('pts_for_match_win', NumberType::class, [
                'label' => 'Combien de points par victoire ?',
                'scale' => 1,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's points per match tie only if swiss type is choosen
            ->add('pts_for_match_tie', NumberType::class, [
                'label' => 'Combien de points par match nul ?',
                'scale' => 1,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's points per game win only if swiss type is choosen
            ->add('pts_for_game_win', NumberType::class, [
                'label' => 'Combien de points par manche gagnée ?',
                'scale' => 1,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's points per game tie only if swiss type is choosen
            ->add('pts_for_game_tie', NumberType::class, [
                'label' => 'Combien de points par égalité sur une manche ?',
                'scale' => 1,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's points per give up only if swiss type is choosen
            ->add('pts_for_bye', NumberType::class, [
                'label' => 'Combien de points si il y a un abandon ?',
                'scale' => 1,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's round number only if swiss type is choosen
            ->add('swiss_rounds', NumberType::class, [
                'label' => 'Combien de rounds dans votre tournoi ?',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's rank by select only for rr and swiss
            ->add('ranked_by', ChoiceType::class, [
                'label' => 'Type du tournoi',
                'choices' => [
                    'Match gagnés' => 'match wins',
                    'Manches gagnées' => 'game wins',
                    'Nombre de points' => 'points scored',
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
             // tournament's points per match win only if Round robin type is choosen
             ->add('rr_pts_for_match_win', NumberType::class, [
                'label' => 'Combien de points par victoire ?',
                'scale' => 1,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's points per match tie only if Round robin type is choosen
            ->add('rr_pts_for_match_tie', NumberType::class, [
                'label' => 'Combien de points par match nul ?',
                'scale' => 1,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's points per game win only if Round robin type is choosen
            ->add('rr_pts_for_game_win', NumberType::class, [
                'label' => 'Combien de points par manche gagnée ?',
                'scale' => 1,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's points per game tie only if Round robin type is choosen
            ->add('rr_pts_for_game_tie', NumberType::class, [
                'label' => 'Combien de points par égalité sur une manche ?',
                'scale' => 1,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's round show radio only if single or double elimination is choosen
            ->add('show_rounds', ChoiceType::class, [
                'label' => 'Afficher le round actuel ?',
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            // tournament's starting notification if open signup is allowed
            ->add('notify_users_when_matches_open', ChoiceType::class, [
                'label' => 'Notifier les inscrits du début du tournoi ?',
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            // tournament's ending notification if open signup is allowed
            ->add('notify_users_when_the_tournament_ends', ChoiceType::class, [
                'label' => 'Notifier les inscrits de la fin du tournoi ?',
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            // tournament's radio for sequentiel pairing
            ->add('sequential_pairings', ChoiceType::class, [
                'label' => 'Sequential pairing ?',
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            // tournament's maximum sign ups
            ->add('signup_cap', NumberType::class, [
                'label' => 'Combien de participants maximum possède le tournoi ?',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's start date
            ->add('start_at', DateType::class, [
                'label' => 'Date de début du tournoi',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's check in limit duration only if open signup allowed
            ->add('check_in_duration', NumberType::class, [
                'label' => "Durée limite de l'enregistrement d'un participant ?",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // tournament's grand final type by select
            ->add('grand_finals_modifier', ChoiceType::class, [
                'label' => 'Type de la grande finale du tournoi',
                'choices' => [
                    'Par défault' => null,
                    'Match simple' => 'single match',
                    'Pas de matchs' => 'skip',
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // Game to add to the tournament
            ->add('game_name', EntityType::class, [
                'label' => 'Jeu',
                'class' => Game::class,
                'choice_label' => 'name',
                //show the game by name's alphabetical order
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC')
                        ->where('g.isVerified = 1');
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
            // Configure your form options here
        ]);
    }
}
