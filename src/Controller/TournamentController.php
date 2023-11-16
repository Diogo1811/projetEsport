<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Form\TournamentType;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TournamentController extends AbstractController
{
    #[Route('/tournament', name: 'app_tournament')]
    public function index(TournamentRepository $tournamentRepository): Response
    {
        $tournaments = $tournamentRepository->findBy([], ['name' => 'ASC']);
        return $this->render('tournament/index.html.twig', [
            'tournaments' => $tournaments,
        ]);
    }

    //add a tournament in the data base
    #[Route('/moderator/tournament/newtournament', name: 'new_tournament')]
    //modify a tournament in the data base
    #[Route('/moderator/tournament/{id}/edittournament', name: 'edit_tournament')]
    public function newEditTournament(Tournament $tournament = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$tournament) {
            $tournament = new Tournament();
            $edit = "";
        }else {
            $edit = $tournament;
        }

        $form = $this->createForm(TournamentType::class, $tournament);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $tournament = $form->getData();

            // tell Doctrine you want to (eventually) save the tournament (no queries yet)
            $entityManager->persist($tournament);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('details_tournament', ['id' => $tournament->getId()]);
        }

        return $this->render('tournament/tournamentForm.html.twig', [
            'form' => $form,
            'edit' => $edit
        ]);

    }

    //function to delete a tournament
    #[Route('/moderator/tournament/{id}/deletetournament', name: 'delete_tournament')]
    public function tournamentDelete(Tournament $tournament, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($tournament);
        $entityManager->flush();

        return $this->redirectToRoute('app_tournament');
    }

    //function to show the details of a tournament (Teams, players and/or editors)
    #[Route('/tournament/{id}', name: 'details_tournament')]
    public function tournamentDetails(Tournament $tournament): Response
    {
        return $this->render('tournament/tournamentDetails.html.twig', [
            'tournament' => $tournament,
        ]);
    }
}
