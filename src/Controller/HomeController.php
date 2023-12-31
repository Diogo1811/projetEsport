<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->orderBySiteCoins();

        return $this->render('home/index.html.twig', [
            'users' => $users,
        ]);
    }

}
