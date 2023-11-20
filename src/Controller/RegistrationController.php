<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\User;
use App\Form\ModifyUserType;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use App\Service\FileUploaderAvatar;
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
use Symfony\Component\Security\Core\User\UserInterface;
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
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager, FileUploaderAvatar $fileUploader): Response
    {
        // admin's email
        $adminMail = 'admin@exemple.com';

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
            // encode the plain password and set it as the user's password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $additionalRole = $request->request->get('userOrTeam');
            if ($additionalRole == "team") {
                $user->setRoles(['ROLE_TEAM']);
            }else{
                // give the user the role user at the registration
                $user->setRoles(['ROLE_USER']);
            }

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

            if ($form->get('email')->getData() == $adminMail) {
               $user->setRoles(['ROLE_ADMIN']);
            }


            // add creationDate to the user
            $user->setCreationDate($now);

            // set isBanned bolean to 0
            $user->setIsBanned(0);

            // set site coins to the welcoming points
            $user->setSiteCoins(100);
            
            // set site coins to the welcoming points
            $user->setIsOfLegalAge(1);
            

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
    public function verifyUserEmail(Request $request, UserRepository $userRepository, TranslatorInterface $translator): Response
    {
        
        $id = $request->query->get('id'); // retrieve the user id from the url

        // Verify the user id exists and is not null
        if (null === $id) {
            return $this->redirectToRoute('app_home');
        }

        $user = $userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user) {
            return $this->redirectToRoute('app_home');
        }



        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse mail a bien été vérifiée.');

        return $this->redirectToRoute('app_login');
    }

}
