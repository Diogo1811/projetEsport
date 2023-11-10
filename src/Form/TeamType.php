<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\Country;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de l'équipe",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => "adresse (champ non obligatoire)",
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('zipCode', TextType::class, [
                'label' => "Code postal",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('logo', FileType::class, [
                'label' => "Logo de l'équipe (champ non obligatoire)",
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // I added the require false because I don't want the user to be forced to add a picture
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Veuillez inserer une image du type jpeg, png ou jpg',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('creationDate', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => "Descriptif de l'équipe (champ non obligatoire)",
                'required' => false,
                'attr' => [
                        'class' => 'form-control'
                ]
            ])
            ->add('linkToOfficialPage', TextareaType::class, [
                'label' => 'Lien vers la page officielle (champ non obligatoire)',
                'required' => false,
                'attr' => [
                        'class' => 'form-control'
                ]
            ])
            ->add('linkToShop', TextareaType::class, [
                'label' => 'Lien vers le shop (champ non obligatoire)',
                'required' => false,
                'attr' => [
                        'class' => 'form-control'
                ]
            ])
            ->add('earning', NumberType::class, [
                'label' => "Gains totaux de l'équipe (champ non obligatoire)",
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
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
            'data_class' => Team::class,
        ]);
    }
}
