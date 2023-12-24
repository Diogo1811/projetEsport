<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\ApiController;
use App\Repository\GameRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Repository\TournamentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(UserRepository $userRepository, TeamRepository $teamRepository, ApiController $apiController, TournamentRepository $tournamentRepository, GameRepository $gameRepository): Response
    {

        // We get the tournaments in my database
        $dataBaseTournaments = $tournamentRepository->findAll();
        $allTournaments = $apiController->getTournaments();
        $users = $userRepository->findBy([],["siteCoins" => "DESC"], 10);
        $teams = $teamRepository->findAll();
        $tournaments = [];
        $games = $apiController->getApiGames($gameRepository);
        $i = 0;
        while ($i < count($allTournaments)) {
            foreach ($allTournaments[$i] as $tournament) {

                if ($dataBaseTournaments) {
           
                    // loop to search every tournament one by one
                    foreach ($dataBaseTournaments as $dataBaseTournament) {
    
                        // condition to check if the tournament in the api database is in my database
                        if ($tournament['name'] === $dataBaseTournament->getName()) {
    
                            // we add the api tournament un the array
                            $tournaments[] = $tournament;
            
                        }
    
                    }
                }
        
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
