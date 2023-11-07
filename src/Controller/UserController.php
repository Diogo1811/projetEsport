<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user/edit/{id}', name: 'edit_user')]
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        //if the user isn't even loggin he gets send back to the login form
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        //if the user isn't the one who this account belongs to he gets send back home
        if($this->getUser() !== $user){
            return $this->redirectToRoute('app_home');
        }

        //create the form
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('profilePicture')->getData()) {
                $user->setprofilePicture(
                    new File($this->getParameter('picture_directory'.'/'.$form->))
                )
            }

            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié');

            return $this->redirectToRoute('app_user', ['id' => $user->getId()]);
        }


        return $this->render('user/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
