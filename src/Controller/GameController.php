<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    //Function to show the list of every game in the dataBase 
    #[Route('/game', name: 'app_game')]
    public function index(GameRepository $gameRepository, ApiController $apiController): Response
    {

        $games = $gameRepository->findBy([], ['name' => 'ASC']);

        $apiGames = $apiController->getApiGames($games);

        return $this->render('game/index.html.twig', [
            'games' => $games,
            'apiGames' => $apiGames
        ]);
    }

    //add a game in the data base
    #[Route('/moderator/game/newgame', name: 'new_game')]
    //modify a game in the data base
    #[Route('/moderator/game/{id}/editgame', name: 'edit_game')]
    public function newEditGame(Game $game = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$game) {
            $game = new Game();
            $edit = "";
        }else {
            $edit = $game;
        }

        $form = $this->createForm(GameType::class, $game);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $game = $form->getData();

            $game->setIsVerified(false);

            // tell Doctrine you want to (eventually) save the game (no queries yet)
            $entityManager->persist($game);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('details_game', ['name' => $game->getName()]);
        }

        return $this->render('game/gameForm.html.twig', [
            'form' => $form,
            'edit' => $edit
        ]);

    }

    // Function to search a game
    #[Route('/searchGame/{srch}', name: 'search_game', methods:['GET'])]
    public function searchAGame(GameRepository $gameRepository, Request $request): JsonResponse
    {
        $srch = $request->attributes->get('srch');
        $games = $gameRepository->searchGame($srch);
       
        return  $this->json($games, 200, [], ['groups'=> ['name', 'id']]);
    }

    //function to delete a game
    #[Route('/moderator/game/{id}/deletegame', name: 'delete_game')]
    public function gameDelete(Game $game, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($game);
        $entityManager->flush();

        return $this->redirectToRoute('app_game');
    }
}
