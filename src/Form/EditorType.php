<?php

namespace App\Form;

use App\Entity\Editor;
use App\Entity\Country;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EditorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //input to add/modify the editor's name in the data base
            ->add('name', TextType::class, [
                'label' => "Nom de l'éditeur",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to add/modify the game's release date in the data base
            ->add('creationDate', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to add/modify the editor's city in the data base
            ->add('city', TextType::class, [
                'label' => "Ville de l'éditeur (champ non obligatoire)",
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to add/modify the editor's country in the data base
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'label' => 'Pays',
                'choice_label' => ucwords('name'),
                // this query_builder allows me to choose in which order i would like the country to be in the select
                // en dql on recupere un object (instance de classe)
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to add/modify the editor's link to web site in the data base
            ->add('linkToOfficialPage', TextareaType::class, [
                'label' => 'Lien vers la page officielle',
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
            'data_class' => Editor::class,
        ]);
    }
}
