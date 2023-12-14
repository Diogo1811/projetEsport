<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Form\TournamentType;
use Doctrine\ORM\EntityManager;
use App\Controller\ApiController;
use App\Repository\GameRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Form\AddPlayersToTournamentType;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TournamentController extends AbstractController
{
    #[Route('/tournament', name: 'app_tournament')]
    public function index(ApiController $apiController, TournamentRepository $tournamentRepository): Response
    {
        $dataBaseTournaments = $tournamentRepository->findAll();
        
        $allTournaments = $apiController->getTournaments();
        $tournaments = [];
        $i = 0;
        while ($i < count($allTournaments)) {

            foreach ($allTournaments[$i] as $tournament) {

                foreach ($dataBaseTournaments as $dataBaseTournament) {
                
                    if ($tournament['name'] === $dataBaseTournament->getName()) {

                        $tournaments[] = $tournament;
        
                    }

                }
            }
            $i++;
        }

        return $this->render('tournament/index.html.twig', [
            'tournaments' => $tournaments
        ]);
    }

    //add a tournament in the data base
    #[Route('/moderator/tournament/newtournament', name: 'new_tournament')]
    public function newTournament(Request $request, ApiController $apiController, EntityManagerInterface $entityManager, GameRepository $gameRepository): Response
    {

        $tournament = new Tournament();

        $form = $this->createForm(TournamentType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $tournamentApi = $form->getData();
            $tournamentApi["start_at"]= $form->getData()["start_at"]->format('c');
            // dd($tournamentApi);
            // $tournament->setGame()

            $gameId = $request->request->get('game');

            $game = $gameRepository->findOneBy(['id' => $gameId]);

            if ($game) {
                $tournament->setGame($game);
            }

            $tournament->setName($tournamentApi["name"]);

            // dd($tournamentApi);
            // dd($tournament);

            $jsonData = json_encode($tournamentApi);


            $apiController->addTournament($jsonData);

            // tell Doctrine you want to (eventually) save the tournament (no queries yet)
            $entityManager->persist($tournament);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('app_tournament');
        }

        return $this->render('tournament/tournamentForm.html.twig', [
            'form' => $form
        ]);
    }

    //function to show the details of a tournament
    #[Route('/tournament/{name}/{url}', name: 'details_tournament')]
    public function tournamentDetails(Tournament $tournament, ApiController $apiController, UserRepository $userRepository, Request $request, TeamRepository $teamRepository, TournamentRepository $tournamentRepository): Response
    {
        $tournamentName = $request->attributes->get('url');
        // dd($tournamentName);
        $tournamentDetails = $apiController->findTournamentByUrl($tournamentName);
        // dd($tournamentDetails); 
        $users = $userRepository->findBy([],["siteCoins" => "DESC"], 10);
        $teams = $teamRepository->findAll();

        $form = $this->createForm(AddPlayersToTournamentType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $participantId = $request->request->get('team');


            $participant = $teamRepository->find($participantId);


            $apiController->addRosterToTournament($tournamentDetails['url'], $participant->getName());

        }

        

        return $this->render('tournament/tournamentDetails.html.twig', [
            'tournament' => $tournament,
            'tournamentDetails' => $tournamentDetails,
            'users' => $users,
            'teams' => $teams,
            'form' => $form
        ]);
    }
}
