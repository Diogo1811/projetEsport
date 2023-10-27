<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
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
    public function index(CountryRepository $countryRepository): Response
    {

        $countries = $countryRepository->findBy([], ['name' => 'ASC']);
        return $this->render('country/index.html.twig', [
            'countries' => $countries,
        ]);
    }

    //add a country in the data base
    #[Route('/country/newcountry', name: 'new_country')]
    //modify a country in the data base
    #[Route('/country/{id}/editcountry', name: 'edit_country')]
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
    #[Route('/country/{id}/deleteCountry', name: 'delete_country')]
    public function countryDelete(Country $country, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($country);
        $entityManager->flush();

        return $this->redirectToRoute('app_country');
    }

    //function to show the details of a country (Teams, players and/or editors)
    #[Route('/country/{id}', name: 'details_country')]
    public function countryDetails(Country $country): Response
    {
        return $this->render('country/countryDetails.html.twig', [
            'country' => $country,
        ]);
    }

}
