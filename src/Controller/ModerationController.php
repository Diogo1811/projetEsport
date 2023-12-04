<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModerationController extends AbstractController
{
    // List of the user only accessible by the admin or the moderators
    #[Route('/moderator', name: 'app_users')]
    public function index(UserRepository $userRepository): Response
    {
        // if the one trying to acces the page isn't a moderator or the admin we send him back home
        // if (!in_array('ROLE_MODERATOR', $this->getUser()->getRoles()) || !in_array('ROLE_MODERATOR', $this->getUser()->getRoles())) {

        //     $this->addFlash('warning', 'Bien essayé mais non !');
        //     return $this->redirectToRoute('app_home');
        // }

        $users = $userRepository->findBy([], ['username' => 'ASC']);

        return $this->render('moderator/listUsers.html.twig', [
            'users' => $users,
        ]);
    }

    // function to ban an user or unban him
    #[Route('/moderator/ban/{id}', name: 'app_ban')]
    public function banAndUnban(User $user, EntityManagerInterface $entityManager): Response
    {
        // if the user is banned we unban him if he his not banned we ban him
        if ($user->isIsBanned()) {

            $user->setIsBanned(false);
            $this->addFlash('success', "L'utilisateur a bien été débloqué");
        }else{

            $user->setIsBanned(true);
            $this->addFlash('success', "L'utilisateur a bien été bloqué");
        }

        $entityManager->persist($user);
        $entityManager->flush();

        

        return $this->redirectToRoute('app_users');
    }

    // function add or remove a moderator role
    #[Route('/moderator/{id}', name: 'app_moderator')]
    public function addRemoveModerator(User $user, EntityManagerInterface $entityManager): Response
    {
       
        if (in_array('ROLE_MODERATOR', $user->getRoles())) {

            $user->setRoles(['ROLE_USER']);
            $this->addFlash('success', "L'utilisateur n'est plus un modérateur");

        }else{

            $user->setRoles(['ROLE_MODERATOR']);
            $this->addFlash('success', "L'utilisateur est devenu un modérateur");
        }

        $entityManager->persist($user);
        $entityManager->flush();

        

        return $this->redirectToRoute('app_users');
    }

}
