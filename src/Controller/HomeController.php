<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\ApiController;
use App\Repository\GameRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Repository\TournamentRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(UserRepository $userRepository, TeamRepository $teamRepository, ApiController $apiController, TournamentRepository $tournamentRepository, GameRepository $gameRepository): Response
    {

        $now = new DateTime();

        // We get the tournaments in my database
        $dataBaseTournaments = $tournamentRepository->findAll();

        // We get the tournaments in my api
        $allTournaments = $apiController->getTournaments();

        // We get the list of the 10 users who have the most siteCoins
        $users = $userRepository->findBy([],["siteCoins" => "DESC"], 10);

        // We get all the teams
        $teams = $teamRepository->findAll();

        // We set an empty array tournament
        $tournaments = [];

        $games = [];
        
        $i = 0;
        while ($i < count($allTournaments)) {
            foreach ($allTournaments[$i] as $tournament) {

                if ($dataBaseTournaments) {
           
                    // loop to search every tournament one by one
                    foreach ($dataBaseTournaments as $dataBaseTournament) {
    
                        // condition to check if the tournament in the api database is in my database
                        if ($tournament['name'] === $dataBaseTournament->getName()) {
    
                            // we add the api tournament un the array
                            $tournaments[] = [$tournament, 'game' => $dataBaseTournament->getGame()->getName()];

                            if (!in_array($dataBaseTournament->getGame()->getName(), $games) ) {
                                $games[] = $dataBaseTournament->getGame()->getName();
                            }
            
                        }
    
                    }
                }
        
            }
            $i++;
        }

        // dd($tournaments);
        $detailsGames = $apiController->getApiGames($games);
        
        foreach ($detailsGames as $detailsGame){
            for ($i = 0 ; $i < count($tournaments); $i++) { 

                // dd($tournaments[$i]['game']);

    
                if ($tournaments[$i]['game'] === $detailsGame['name']) {
                    $tournaments[$i] += ['urlCoverGame' => $detailsGame['cover']['url']];
                }
            }
            
        }
       
        return $this->render('home/index.html.twig', [
            'users' => $users,
            'teams' => $teams,
            'tournaments' => $tournaments,
            'now' => $now
        ]);
    }

}
