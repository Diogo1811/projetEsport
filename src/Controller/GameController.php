<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    //Function to show the list of every game in the dataBase 
    #[Route('/game', name: 'app_game')]
    public function index(GameRepository $gameRepository): Response
    {

        $games = $gameRepository->findBy([], ['name' => 'ASC']);
        return $this->render('game/index.html.twig', [
            'games' => $games,
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

            // tell Doctrine you want to (eventually) save the game (no queries yet)
            $entityManager->persist($game);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('app_game');
        }

        return $this->render('game/gameForm.html.twig', [
            'form' => $form,
            'edit' => $edit
        ]);

    }

    //function to delete a game
    #[Route('/moderator/game/{id}/deletegame', name: 'delete_game')]
    public function gameDelete(Game $game, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($game);
        $entityManager->flush();

        return $this->redirectToRoute('app_game');
    }

   //function to show the details of an game (games, country...)
//    #[Route('/game/{id}', name: 'details_game')]
//    public function gameDetails(Game $game): Response
//    {
//        return $this->render('game/gameDetails.html.twig', [
//            'game' => $game,
//        ]);
//    }
}
