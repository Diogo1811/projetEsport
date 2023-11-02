<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\User;
use App\Service\FileUploader;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        // new variable which will be set to give the timezone that i want
        $timezone = new DateTimeZone('Europe/Paris');

        // new variable which will be set to give the current date
        $now = new DateTime();

        // we set the var who will give me the curreent date whith the timezone that i want
        $now->setTimezone($timezone);

        // set a new user
        $user = new User();

        //create the form for the user
        $form = $this->createForm(RegistrationFormType::class, $user);

        // check if the form is valid
        $form->handleRequest($request);

        // condition to check if the form is submitted and if it's valid
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // set the var picture
            $profilePicture = $form->get('profilePicture')->getData(); 

            // condition to check if the user entered a profile picture
            if ($profilePicture) {

                // we upload the picture
                $profilePictureName = $fileUploader->upload($profilePicture);

                //and we set the picture for the user
                $user->setprofilePicture($profilePictureName);

            }else {
                
                // if the user didn't add a profile pic we just give him the default one
                $user->setProfilePicture("defaultProfilePicture.jpg");                
            }


            // add creationDate to the user
            $user->setCreationDate($now);

            // set isBanned bolean to 0
            $user->setIsBanned(0);

            // set site coins to the welcoming points
            $user->setSiteCoins(100);

            // add the data to the newly created user
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    // the email address where the email originate from (usually the admin)
                    ->from(new Address('admin@exemple.com', 'Admin Site'))
                    // the user's email adress
                    ->to($user->getEmail())
                    // subject of the sended email
                    ->subject('Please Confirm your Email')
                    // link to verify the email address
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            // return $userAuthenticator->authenticateUser(
            //     $user,
            //     $authenticator,
            //     $request
            // );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse mail a bien été vérifiée.');

        return $this->redirectToRoute('app_register');
    }
}
