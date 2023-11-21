<?php

namespace App\Controller;

use GuzzleHttp\Client;
use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\TeamRepository;
use App\Repository\EditorRepository;
use App\Repository\PlayerRepository;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CountryController extends AbstractController
{
    //Function to show the list of every Country in the dataBase 
    #[Route('/country', name: 'app_country')]
    public function index(EditorRepository $editorRepository, TeamRepository $teamRepository, PlayerRepository $playerRepository): Response
    {
        $apiCountries = 'https://restcountries.com/v3.1/all';

        // Create a Guzzle client
        $client = new Client();

        // Api 4 games call 
        $response = $client->request('GET', $apiCountries);

        $data = json_decode($response->getBody(), true);

        // dd($data[0]['translations']);

        $editors = $editorRepository->findBy([], ['name' => 'ASC']);
        $teams = $teamRepository->findBy([], ['name' => 'ASC']);
        $players = $playerRepository->findBy([], ['nickname' => 'ASC']);


        return $this->render('country/index.html.twig', [
            'countries' => $data,
            'editors' => $editors,
            'teams' => $teams,
            'players' => $players

        ]);
    }

    //add a country in the data base
    #[Route('/moderator/country/newcountry', name: 'new_country')]
    //modify a country in the data base
    #[Route('/moderator/country/{id}/editcountry', name: 'edit_country')]
    public function newEditCountry(Country $country = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$country) {
            $country = new Country();
            $edit = "";
        }else {
            $edit = $country;
        }

        $form = $this->createForm(CountryType::class, $country);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $country = $form->getData();

            // tell Doctrine you want to (eventually) save the country (no queries yet)
            $entityManager->persist($country);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('app_country');
        }

        return $this->render('country/countryForm.html.twig', [
            'form' => $form,
            'edit' => $edit
        ]);

    }

    //function to delete a country
    #[Route('/moderator/country/{id}/deleteCountry', name: 'delete_country')]
    public function countryDelete(Country $country, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($country);
        $entityManager->flush();

        return $this->redirectToRoute('app_country');
    }

    //function to show the details of a country (Teams, players and/or editors)
    #[Route('/country/{id}', name: 'details_country')]
    public function countryDetails(EditorRepository $editorRepository, TeamRepository $teamRepository, PlayerRepository $playerRepository, Request $request): Response
    {

        $countryId = $request->attributes->get('id');


        $apiCountries = 'https://restcountries.com/v3.1/alpha/'.$countryId;

        // Create a Guzzle client
        $client = new Client();

        // Api 4 games call 
        $response = $client->request('GET', $apiCountries);

        $data = json_decode($response->getBody(), true);

        $country = $data[0];

        // dd($data);


        $allEditors = $editorRepository->findBy([], ['name' => 'ASC']);
        $allTeams = $teamRepository->findBy([], ['name' => 'ASC']);
        $allPlayers = $playerRepository->findBy([], ['nickname' => 'ASC']);

        $editors = [];
        $teams = [];
        $players = [];

        
        foreach ($allEditors as $editor) {
            if ($editor->getCountry() == $countryId) {
                array_push($editors, $editor);
            }
        }

        foreach ($allTeams as $team) {
            if ($team->getCountry() == $countryId) {
                array_push($teams, $team);
            }
        }

        foreach ($allPlayers as $player) {
            if ($player->getCountry() == $countryId) {
                array_push($players, $player);
            }
        }

        return $this->render('country/countryDetails.html.twig', [
            'editors' => $editors,
            'teams' => $teams,
            'players' => $players,
            'country' => $country
        ]);
    }

}
