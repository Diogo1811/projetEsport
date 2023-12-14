<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\ApiController;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(UserRepository $userRepository, TeamRepository $teamRepository, ApiController $apiController): Response
    {
        $allTournaments = $apiController->getTournaments();
        $users = $userRepository->findBy([],["siteCoins" => "DESC"], 10);
        $teams = $teamRepository->findAll();
        $tournaments = [];
        $i = 0;
        while ($i < count($allTournaments)) {
            foreach ($allTournaments[$i] as $tournament) {
                $tournaments[] = $tournament;
            }
            $i++;
        }


        return $this->render('home/index.html.twig', [
            'users' => $users,
            'teams' => $teams,
            'tournaments' => $tournaments
        ]);
    }

}
