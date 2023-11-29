<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Service\FileUploaderLogo;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayerController extends AbstractController
{
    #[Route('/player', name: 'app_player')]
    public function index(PlayerRepository $playerRepository): Response
    {
        $players = $playerRepository->findBy([], ['lastName' => 'ASC']);
        return $this->render('player/index.html.twig', [
            'players' => $players,
        ]);
    }

    //add a player in the data base
    #[Route('/userTeam/player/newplayer', name: 'new_player')]
    //modify a player in the data base
    #[Route('/userTeam/player/{id}/editplayer', name: 'edit_player')]
    public function newEditPlayer(Player $player = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // if the player is set at the start it means we are in a modify player and not in an add
        if (!$player) {
            $player = new Player();
            $edit = "";
        }else {
            $edit = $player;
        }

        if ($this->IsGranted('ROLE_MODERATOR') || $this->getUser()->getTeam()){
            $form = $this->createForm(PlayerType::class, $player);
            // dd($form);

            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {

                
                $player = $form->getData();

                $country = $request->request->get('country');

                if ($country) {
                    $player->setCountry($country);
                }

                // tell Doctrine you want to (eventually) save the player (no queries yet)
                $entityManager->persist($player);

                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();

                return $this->redirectToRoute('details_player', ['id' => $player->getId()]);
            }
        
            return $this->render('player/playerForm.html.twig', [
                'form' => $form,
                'edit' => $edit,
                'playerId' => $player->getId(),
            ]);
        }else {
            $this->addFlash('error', 'bien essayé!');
            return $this->redirectToRoute('app_home');
        }
    }

    //function to show the details of a player
    #[Route('/player/{id}', name: 'details_player')]
    public function playerDetails(Player $player): Response
    {
        return $this->render('player/playerDetails.html.twig', [
            'player' => $player,
        ]);
    }
}
