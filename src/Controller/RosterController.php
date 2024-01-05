<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\Player;
use App\Entity\Roster;
use App\Form\RosterType;
use App\Repository\GameRepository;
use App\Repository\RosterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RosterController extends AbstractController
{
    #[Route('/roster', name: 'app_roster')]
    public function index(): Response
    {
        return $this->render('roster/index.html.twig', [
            'controller_name' => 'RosterController',
        ]);
    }

    //add a roster in the data base
    #[Route('/userTeam/roster/{id}/newroster', name: 'new_roster')]
    //edit a roster in the data base
    #[Route('/userTeam/roster/{idRoster}/{id}/editroster', name: 'edit_roster')]
    public function newEditRoster(Team $team, Roster $roster = null, Request $request, EntityManagerInterface $entityManager, RosterRepository $rosterRepository, GameRepository $gameRepository): Response
    {
        $idRoster = $request->attributes->get('idRoster');
        $roster = $rosterRepository->findOneBy(['id' => $idRoster]);
        // dd($roster);

        // if the roster is set at the start it means we are in a modify roster and not in an add
        if (!$roster) {
            $roster = new Roster();
            $edit = "";

            //we link the team to the roster
            $roster->setTeam($team);
 
        }else {
            $edit = $roster;
        }
 
        
        if ($this->IsGranted('ROLE_MODERATOR') || $this->getUser()->getTeam() == $team) {
            
            $form = $this->createForm(RosterType::class, $roster);
    
            $form->handleRequest($request);

            // $srch = $request->request->get('searchGameInput');

            // searchBar to check the games
            // $games = json_encode($gameRepository->findBy(['name' => $srch]));
            
            if ($form->isSubmitted() && $form->isValid()) {
    
                if (!$edit) {

                    //add the roster to the team
                    $team->addRoster($roster);
                }

                
                $roster = $form->getData();

                $gameId = $request->request->get('game');

                $game = $gameRepository->findOneBy(['id' => $gameId]);

                if ($game) {
                    $roster->setGame($game);
                }
    
                // tell Doctrine you want to (eventually) save the roster (no queries yet)
                $entityManager->persist($roster);
    
                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();
    
                return $this->redirectToRoute('details_team', ['id' => $team->getId()]);
            }
    
            return $this->render('roster/rosterForm.html.twig', [
                'form' => $form,
                'team' => $team,
                'rosterId' => $roster->getId(),
                'edit' => $edit
            ]);

        }else {

            $this->addFlash('error', 'bien essayé !');
            return $this->redirectToRoute('app_home');

        }
    }


    //function to delete a roster
    #[Route('/userTeam/roster/{id}/deleteroster', name: 'delete_roster')]
    public function rosterDelete(roster $roster, EntityManagerInterface $entityManager): Response
    {
        $teamId = $roster->getTeam()->getId();

        // prepare the request
        $entityManager->remove($roster);

        // execute the request
        $entityManager->flush();

        // send the user to the list of rosters
        return $this->redirectToRoute('details_team', ['id' => $teamId]);
    }
}
