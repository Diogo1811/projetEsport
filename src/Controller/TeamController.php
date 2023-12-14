<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\User;
use App\Form\TeamType;
use App\Service\FileUploaderLogo;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TeamController extends AbstractController
{
    #[Route('/team', name: 'app_team')]
    public function index(TeamRepository $teamRepository): Response
    {
        // $teams is an object that will contain every team in the database
        $teams = $teamRepository->findBy([], ['name' => 'ASC']);

        // the user will be sent to a page of a list of teams
        return $this->render('team/index.html.twig', [

            'teams' => $teams,

        ]);

    }

    //add a team in the data base
    #[Route('/userTeam/team/newteam', name: 'new_team')]
    //modify a team in the data base
    #[Route('/userTeam/team/{id}/editteam', name: 'edit_team')]
    public function newEditTeam(Team $team = null, Request $request, EntityManagerInterface $entityManager, FileUploaderLogo $fileUploader): Response
    {
        // if the team is set at the start it means we are in a modify team and not in an add
        if (!$team) {

            // since we are in a new team form we create a new object of the class team that we will call $team
            $team = new Team();

            // we set the edit with nothing to manage the title of the form
            $edit = "";
        
        }else {
            
            // On an edit a user who doesn't have a team or has one but it's not the one in the edit will be send off
            if(!$this->getUser()->getTeam() || $this->getUser()->getTeam() != $team) {

                $this->addFlash('error', 'bien essayé !');

                return $this->redirectToRoute('app_home');
    
            }

            // here we are in an edit team so we set the edit with the team to manage the title of the form 
            $edit = $team;

        }

        // creation of the form
        $form = $this->createForm(TeamType::class, $team);

        // handle the form once submitted
        $form->handleRequest($request);
        
        // if the form is sumbmitted and the values are valid we start the add or modification in the database
        if ($form->isSubmitted() && $form->isValid()) {

            // set the var picture
            $logo = $form->get('logo')->getData(); 
            
            // if there's a logo
            if ($logo) {
    
                // we upload the picture
                $logoName = $fileUploader->upload($logo);
    
                //and we set the picture for the team
                $team->setlogo($logoName);
                
                // if there's no picture added 
            }else{

                // A default picture is added to the team
                $team->setLogo('defaultlogo.jpg');
            }

            // we set country with the value of the input 'country'
            $country = $request->request->get('country');

            // if country is set
            if ($country) {

                // we set the country as the team's country
                $team->setCountry($country);
            }

            // if we are in a new team created and that team was created by a user with ROLE_TEAM we set this team as its favorite
            if (!$edit && $this->IsGranted('ROLE_TEAM')) {
                // dd("you are in the if that says you are the creator");
                $this->getUser()->setTeam($team);
            }

            // prepare the request
            $entityManager->persist($team);

            // executes the request
            $entityManager->flush();

            // user is sent 
            return $this->redirectToRoute('details_team', ['id' => $team->getId()]);
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

        // prepare the request
        $entityManager->remove($team);

        // execute the request
        $entityManager->flush();

        // send the user to the list of teams
        return $this->redirectToRoute('app_team');
    }

    //function to add a team to an user
    #[Route('/team/{id}/add', name: 'add_team')]
    public function addTeamToUser(Team $team, EntityManagerInterface $entityManager, UserInterface $user = null): Response
    {
        // the user is the one that clicked on the button
        $user = $this->getUser();

        // condition if the user doesn't have a team
        if (!$user->getTeam()){

            // add the team to the user
            $user->setTeam($team);
    
            // prepare the request
            $entityManager->persist($team);
            
            // execute the request
            $entityManager->flush();

            // Message to the user
            $this->addFlash('success', 'Vous avez ajouté '.$team.' comme équipe favorite');

            // send him back to the team détails
            return $this->redirectToRoute('details_team', ['id' => $team->getId()]);

            // if the team is already the user's favorite
        }else if ($user->getTeam()->getId() == $team->getId()) {
            
            // remove the team as it's favorite
            $user->setTeam(null);
    
            // prepare the request
            $entityManager->persist($team);

            // execute the request
            $entityManager->flush();

            // message to the user
            $this->addFlash('success', $team." n'est plus votre équipe favorite"  );

            // send him back to the team détails
            return $this->redirectToRoute('details_team', ['id' => $team->getId()]);

            //if the user wants to add a team but already has one
        }else{

            // message to the user
            $this->addFlash('error', 'Vous avez déjà une équipe favorite, vous pouvez la retirer si vous souhaitez');
            
            // send him back to the team détails
            return $this->redirectToRoute('details_team', ['id' => $team->getId()]);
        }
    }

    // Function to search a team
    #[Route('/searchTeam/{srch}', name: 'search_team', methods:['GET'])]
    public function searchATeam(TeamRepository $teamRepository, Request $request): JsonResponse
    {
        $srch = $request->attributes->get('srch');
        $teams = $teamRepository->searchTeam($srch);
       
        return  $this->json($teams, 200, [], ['groups'=> ['name', 'id']]);
    }

    //function to show the details of a team
    #[Route('/team/{id}', name: 'details_team')]
    public function teamDetails(Team $team): Response
    {

        // send the user to the page of the details of the team
        return $this->render('team/teamDetails.html.twig', [

            // this $team var is the object of the selected team which means that all the information about the team that are in the database areon this var
            'team' => $team,
        ]);
    }

}
