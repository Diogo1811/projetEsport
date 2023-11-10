<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //input to add the username in the data base
            ->add('username', TextType::class, [
                'label' => "Pseudo",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to add the email in the data base
            ->add('email', TextType::class, [
                'label' => "E-mail",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //input to add the password in the data base
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                //here i put the regex that asks between 12 and 64 characters with at least an uppercase, a lowercase, a number and a special char
                'constraints' => [
                    new Regex('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{12,64}$/')
                ],
                //if the password and the password validation aren't the same this message will be sent
                'invalid_message' => 'Les mots de passes doivent correspondre !',
                'options' => ['attr' => ['class' => 'form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répétez le mot de passe'],
            ])
            //input to add the profilPicture in the data base
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
            //checkbox for the web site terms agreement
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Pour pouvoir s'inscire votre accord est nécéssaire.",
                    ]),
                ],
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
