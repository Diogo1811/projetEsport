<?php

namespace App\Form;

use App\Entity\SocialMediaAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SocialMediaAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom du réseau Social",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('linkToSocialMedia', TextareaType::class, [
                'label' => "liens vers ce réseau",
                'attr' => [
                        'class' => 'form-control'
                ]
            ])
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SocialMediaAccount::class,
        ]);
    }
}
