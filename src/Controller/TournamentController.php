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
use App\Form\EncounterType;
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

        // We get the tournaments in my database
        $dataBaseTournaments = $tournamentRepository->findAll();
        
        // We get the tournaments in the api database
        $allTournaments = $apiController->getTournaments();
        
        // We set an empty array of tournaments to add every tournaments that are in my database and in the api database
        $tournaments = [];

        // loop beacause the data retrieve from the api sends arrays inside other arrays so i want to sort what will be send to the template
        for ($i=0; $i < count($allTournaments); $i++) { 

            // loop to search every tournament one by one
            foreach ($dataBaseTournaments as $dataBaseTournament) {

                // condition to check if the tournament in the api database is in my database
                if ($allTournaments[$i]['tournament']['name'] === $dataBaseTournament->getName()) {

                    // we add the api tournament un the array
                    $tournaments[] = $allTournaments[$i]['tournament'];
    
                }

            }
        }

        return $this->render('tournament/index.html.twig', [

            // we send the array to the template
            'tournaments' => $tournaments,

            'tournamentsDB' => $dataBaseTournaments

        ]);
    }

    //add a tournament in the data base
    #[Route('/moderator/tournament/newtournament', name: 'new_tournament')]
    public function newTournament(Request $request, ApiController $apiController, EntityManagerInterface $entityManager, GameRepository $gameRepository): Response
    {
        // Creation of a new object of tournament in my database
        $tournament = new Tournament();
        
        // Creation of the form to create a tournament
        $form = $this->createForm(TournamentType::class);

        $form->handleRequest($request);
        
        // condition to check if the user has submitted the form and if it's valid
        if ($form->isSubmitted() && $form->isValid()) {

            // we take the data gave by the $form and set it in a var
            $tournamentApi = $form->getData();

            // the api ask for a certain format for the starting date so we take the input start at we format it with the c and set it to the index start_at of the array $tournament api
            $tournamentApi["start_at"]= $form->getData()["start_at"]->format('c');

            // we get the id of the game of the tournament since the api doesn't allow the add of the game it's not in the tounamentType so not in the $form
            $gameId = $request->request->get('game');

            filter_var($gameId, FILTER_SANITIZE_NUMBER_INT);
            
            // find the object game thanks to his id
            $game = $gameRepository->findOneBy(['id' => $gameId]);
            
            // condition to check if the var $game is not empty
            if ($game) {
                
                // we set the game to the tournament in my database
                $tournament->setGame($game);
                
            }else {

                $this->addFlash('error', 'Veuillez entrer un jeu pour le tournoi !');
                return $this->redirectToRoute('new_tournament');
            }

            // we get the number minimum of players in a roster since the api doesn't allow the add of the game it's not in the tounamentType so not in the $form
            $numberOfPlayer = $request->request->get('numberPlayer');

            filter_var($numberOfPlayer, FILTER_SANITIZE_NUMBER_INT);

            // condition to check if the var $numberOfPlayer is not empty
            if ($numberOfPlayer > 0) {
                
                // we set the number of players in a participing roster to the tournament in my database
                $tournament->setNumberPlayer($numberOfPlayer);
                
            }else {

                $this->addFlash('error', 'Le nombre de joueur ne peut pas être nul ou négatif !');
                return $this->redirectToRoute('new_tournament');
            }

            // we set the name of the tournament in my database
            $tournament->setName($tournamentApi["name"]);

       
            // we encode the retrieved data from the form to send it to the api
            $jsonData = json_encode($tournamentApi);

            // we use a function created in the apicontroller that allows, me thanks to the data, to create a new tournament
            $apiController->addTournament($jsonData);

            // tell Doctrine you want to (eventually) save the tournament (no queries yet)
            $entityManager->persist($tournament);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            // redirection to the list of tournaments
            return $this->redirectToRoute('app_tournament');
            
        }

        return $this->render('tournament/tournamentForm.html.twig', [

            // send the TournamentType form
            'form' => $form
            
        ]);
    }

    //function to show the details of a tournament
    #[Route('/tournament/{name}/{url}', name: 'details_tournament')]
    public function tournamentDetails(Tournament $tournament, ApiController $apiController, UserRepository $userRepository, Request $request, TeamRepository $teamRepository): Response
    {
        // We get the tournament's url to search the tournament in the api
        $tournamentUrl = $request->attributes->get('url');

        // Thanks to the touranment's url we can get the tournament
        $tournamentDetails = $apiController->findTournamentByUrl($tournamentUrl);

        // Thanks to the touranment's url we can get the tournament's matches
        $tournamentMatches = $apiController->findMatchesByTournamentUrl($tournamentUrl);

        // dd($tournamentMatches);

        // We use once again the tournament's url but this time to retrive all the participants
        $tournamentParticipantsAPI = $apiController->findParticipantsByTournamentUrl($tournamentUrl);

        // dd($tournamentParticipantsAPI);

        // We create the empty array that we will send to the template once we put all the paticipants in
        $tournamentParticipants = [];

        // We take every name of the teams in the tournament
        for ($i=0; $i < count($tournamentParticipantsAPI); $i++) { 

            // We search every team in the tournament in my database thanks to the names 
            $teamInTournament = $teamRepository->findOneBy(['name' => $tournamentParticipantsAPI[$i]['name']]);

            // set the array with every object team found 
            $tournamentParticipants[] = $teamInTournament;

        }

        // get 10 users and sort them by the site coins that they have
        $users = $userRepository->findBy([],["siteCoins" => "DESC"], 10);

        // get all the teams in the database
        $teams = $teamRepository->findAll();

        // Creation of the form to add participants to the tournament
        $form = $this->createForm(AddPlayersToTournamentType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            // we get the team selected id
            $participantId = $request->request->get('team');

            // find the team in the database thanks to his id
            $participant = $teamRepository->find($participantId);

            $playerActuallyInRosters = [];

            // loop to check every roster linked to the team
            foreach ($participant->getRosters() as $roster) {

                
                // if the team has a roster with the same game as the tournament
                if ($roster->getGame()->getName() == $tournament->getGame()->getName()) {

                    foreach ($roster->getPlayerRosters() as $playerRoster) {

                        // dd($playerRoster->getPlayingEndDate());

                        if (!$playerRoster->getPlayingEndDate()) {
                            $playerActuallyInRosters[] = $playerRoster;
                        }
                        
                    }
                    if (count($playerActuallyInRosters) < $tournament->getNumberPlayer()) {

                        // this message is displayed if the team has no roster with the same game as the tournament
                        $this->addFlash('error', $participant." n'as pas assez de joueurs dans son roster pour ".$tournament->getGame()->getName()." qui est le jeu sur lequel se déroule ce tournoi! Il faut ".$tournament->getNumberPlayer()." joueurs pour s'inscrire!");
                    }else{

                        // We use the function created in the ApiController to add a participant to a tournament
                        $apiController->addRosterToTournament($tournamentDetails['url'], $participant->getName());
    
                        // Success message to inform the user that the participant as been added
                        $this->addFlash('success', $participant." a été ajouter au tournoi !");
    
                        // return to the page to refresh and show the team added
                        return $this->redirectToRoute('details_tournament', ['name' => $tournament->getName(), "url" => $tournamentUrl]);
                    }
                            
                }
            }

            // this message is displayed if the team has no roster with the same game as the tournament
            $this->addFlash('error', $participant." n'as pas de roster pour ".$tournament->getGame()->getName()." qui est le jeu sur lequel se déroule ce tournoi !");

        }




        

        return $this->render('tournament/tournamentDetails.html.twig', [

            // tournament in my database
            'tournament' => $tournament,

            // tournament in database
            'tournamentDetails' => $tournamentDetails,

            // 10 users 
            'users' => $users,

            // all teams in the database
            'teams' => $teams,

            // add a participant form
            'form' => $form,

            // teams participating in the tournament
            'participants' => $tournamentParticipants,
            
            // teams participating in the tournament
            'apiParticipants' => $tournamentParticipantsAPI,

            // list of tournament's matches
            'matches' => $tournamentMatches
        ]);
    }

    // Match results function
    #[Route('/moderator/match/{id}/{url}', name: 'update_score')]
    public function updateScore(ApiController $apiController, Request $request): Response
    {

        // We get the tournament's url to search the tournament in the api
        $tournamentUrl = $request->attributes->get('url');
        
        // We get the tournament's url to search the tournament in the api
        $matchId = $request->attributes->get('id');

        // we find in the api the match we want to update
        $match = $apiController->findMatchById($matchId, $tournamentUrl);
        
        // we find in the api the match we want to update
        $tournament = $apiController->findTournamentByUrl($tournamentUrl);

        // dd($tournament);

        // Creation of the form to add participants to the tournament
        $form = $this->createForm(EncounterType::class);


        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            // we get the team selected id
            $scoreTeamOne = $form->get('scoreTeamOne')->getData();
            $scoreTeamTwo = $form->get('scoreTeamTwo')->getData();

            // dd($form->getData());
            // dd($scoreTeamTwo);
            // dd($scoreTeamOne);

            if ($scoreTeamOne > $scoreTeamTwo) {

                $winner = $match['player1_id'];

            }else {

                $winner = $match['player2_id'];
            }

            $scoreMatch = $scoreTeamOne.'-'.$scoreTeamTwo;

            $apiController->addScoreToMatch($tournamentUrl, $matchId, $scoreMatch, $winner);

            return $this->redirectToRoute('details_tournament', ['name' => $tournament['name'], "url" => $tournamentUrl]);




            // this message is displayed if the team has no roster with the same game as the tournament
            // $this->addFlash('error', $participant." n'as pas de roster pour ".$tournament->getGame()->getName()." qui est le jeu sur lequel se déroule ce tournoi !");

        }




        

        return $this->render('match/addScore.html.twig', [

            // add a participant form
            'form' => $form,
        ]);
    }

    // start tournament  function
    #[Route('/moderator/tournament/start/{url}', name: 'start_tournament')]
    public function startTournament(ApiController $apiController, Request $request){

        // We get the tournament's url to search the tournament in the api
        $tournamentUrl = $request->attributes->get('url');

        // we find in the api the match we want to update
        $tournament = $apiController->findTournamentByUrl($tournamentUrl);
        
        // We call the function on the api controller to start the tournament
        $apiController->startATournament($tournamentUrl);
        
        return $this->redirectToRoute('details_tournament', ['name' => $tournament['name'], "url" => $tournamentUrl]);
    }
}
