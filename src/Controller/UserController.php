<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploaderAvatar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    // This is the methode to modify an user's username or profile picture
    #[Route('/user/edit/{id}', name: 'edit_user')]
    public function edit(User $user, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager, FileUploaderAvatar $fileUploader, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // if the user isn't even loggin he gets send back to the login form
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // if the user isn't the one who this account belongs to or at leats a moderator he gets send back home
        if($this->getUser() !== $user && !in_array('ROLE_MODERATOR', $this->getUser()->getRoles()) && !in_array('ROLE_ADMIN', $this->getUser()->getRoles())){

            return $this->redirectToRoute('app_home');
        }

        // create the form
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        

        if ($form->isSubmitted() && $form->isValid()) {
            
            $enteredPassword = $form->get('plainPassword')->getData();

            if ($userPasswordHasher->isPasswordValid($user, $enteredPassword) || in_array('ROLE_MODERATOR', $this->getUser()->getRoles()) || in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                
                // set the var picture
                $profilePicture = $form->get('profilePicture')->getData(); 

                // We check if the user added a new profile picture
                if ($profilePicture) {

                    // we upload the picture
                    $profilePictureName = $fileUploader->upload($profilePicture);

                    // and we set the picture for the user
                    $user->setProfilePicture($profilePictureName);
                }

                // $user->setUsername($form->get('username')->getData());

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre profil a bien été modifié');

                return $this->redirectToRoute('app_user', ['id' => $user->getId()]);
            }else {

                // if the password entered isn't the one in the data base we send this message error
                $this->addFlash('warning', 'Le mot de passe est éroné !');
            }
            
        }


        return $this->render('user/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
