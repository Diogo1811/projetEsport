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


        $editors = $editorRepository->findBy([], ['name' => 'ASC']);
        $teams = $teamRepository->findBy([], ['name' => 'ASC']);
        $players = $playerRepository->findBy([], ['nickname' => 'ASC']);

        return $this->render('country/countryDetails.html.twig', [
            'editors' => $editors,
            'teams' => $teams,
            'players' => $players,
            'infos' => $country
        ]);
    }

    

}
