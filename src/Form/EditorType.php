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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EditorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //input to add/modify the editor's name on the data base
            ->add('name', TextType::class, [
                'label' => "Nom de l'éditeur",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to add/modify the editor's link to web site on the data base
            ->add('linkToOfficialPage', TextareaType::class, [
                'label' => 'Lien vers la page officielle',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to add/modify the editor's country on the data base
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'label' => 'Pays',
                'choice_label' => 'name',
                // this query_builder allows me to choose in which order i would like the country to be in the select
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
            'data_class' => Editor::class,
        ]);
    }
}
