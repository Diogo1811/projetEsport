<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //input to modify the username in the data base
            ->add('username', TextType::class, [
                'label' => "Pseudo",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to modify the profilPicture in the data base
            ->add('profilePicture', FileType::class, [
                'label' => "Votre photo de profil (champ non obligatoire)",
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
            //input to check the password in the data base
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Mot de passe',
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
            'data_class' => User::class,
        ]);
    }
}
