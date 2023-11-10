<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Service\FileUploaderLogo;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TeamController extends AbstractController
{
    #[Route('/team', name: 'app_team')]
    public function index(TeamRepository $teamRepository): Response
    {
        $teams = $teamRepository->findBy([], ['name' => 'ASC']);
        return $this->render('team/index.html.twig', [
            'teams' => $teams,
        ]);
    }

    //add a team in the data base
    #[Route('/moderator/team/newteam', name: 'new_team')]
    //modify a team in the data base
    #[Route('/moderator/team/{id}/editteam', name: 'edit_team')]
    public function newEditTeam(Team $team = null, Request $request, EntityManagerInterface $entityManager, FileUploaderLogo $fileUploader): Response
    {
        // if the team is set at the start it means we are in a modify team and not in an add
        if (!$team) {
            $team = new Team();
            $edit = "";
        }else {
            $edit = $team;
        }

        $form = $this->createForm(TeamType::class, $team);

        // $form->handleRequest($request);
        dd($form->handleRequest($request));
        
        if ($form->isSubmitted() && $form->isValid()) {

            // set the var picture
            $logo = $form->get('logo')->getData();  
            
            
            // condition to check if a logo was added
            if ($logo && $team->getLogo() != $logo) {

                // we upload the picture
                $logoName = $fileUploader->upload($logo);

                //and we set the picture for the team
                $team->setlogo($logoName);

            }else{
                
                // if there's no logo added we insert our own basic logo
                $team->setLogo("defaultlogo.jpg");  

            }
            

            // tell Doctrine you want to (eventually) save the team (no queries yet)
            $entityManager->persist($team);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('app_team');
        }

        return $this->render('team/teamForm.html.twig', [
            'form' => $form,
            'edit' => $edit
        ]);
    }

    //function to delete a team
    #[Route('/moderator/team/{id}/deleteteam', name: 'delete_team')]
    public function teamDelete(Team $team, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($team);
        $entityManager->flush();

        return $this->redirectToRoute('app_team');
    }

    //function to show the details of a team
    #[Route('/team/{id}', name: 'details_team')]
    public function teamDetails(Team $team): Response
    {
        return $this->render('team/teamDetails.html.twig', [
            'team' => $team,
        ]);
    }

}
