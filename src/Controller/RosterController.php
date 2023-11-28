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
    #[Route('/moderator/roster/{id}/newroster', name: 'new_roster')]
    //edit a roster in the data base
    #[Route('/moderator/roster/{idRoster}/{id}/editroster', name: 'edit_roster')]
    public function newEditRoster(Team $team, Roster $roster = null, Request $request, EntityManagerInterface $entityManager, RosterRepository $rosterRepository, GameRepository $gameRepository): Response
    {
        $idRoster = $request->attributes->get('idRoster');
        $roster = $rosterRepository->findOneBy(['id' => $idRoster]);
        // dd($team);
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

            // Check if a new player is added
            // $newPlayerData = $form->get('newPlayer')->getData();

            // if ($newPlayerData) {
            //     $newPlayer = new Player();
            //     // Set player data from the form
            //     $newPlayer->setLastName($newPlayerData['lastName']);
            //     $newPlayer->setFirstname($newPlayerData['firstname']);
            //     $newPlayer->setFirstname($newPlayerData['nickname']);
            //     $newPlayer->setGender($newPlayerData['gender']);
            //     $newPlayer->setBiography($newPlayerData['biography']);
            //     $newPlayer->setBirthDate($newPlayerData['birthDate']);
            //     $newPlayer->setEarning($newPlayerData['earning']);
            //     $newPlayer->addSocialMediaAccount($newPlayerData['socialMediaAccounts']);
            //     $newPlayer->addCountry($newPlayerData['countries']);
            //     // Set other player properties

            // }
            
             
            $roster = $form->getData();

            $game = $request->request->get('game');

            if ($game) {
                $roster->setGame($game);
            }
 
            // tell Doctrine you want to (eventually) save the roster (no queries yet)
            $entityManager->persist($roster);
 
            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
 
            return $this->redirectToRoute('app_team');
        }
 
        return $this->render('roster/rosterForm.html.twig', [
            'form' => $form,
            'team' => $team,
            'rosterId' => $roster->getId(),
            'edit' => $edit
        ]);
    }
}
