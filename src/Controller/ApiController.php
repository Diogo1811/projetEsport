<?php 

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{
    #[Route('/detailsGame/{name}', name: 'details_game')]
    public function proxyActionGameApi(Game $game, Request $request)
    {
        // Twitch token link
        $accessTokenUrl = 'https://id.twitch.tv/oauth2/token';

        // Get the external API URL from the request
        $externalApiUrl = 'https://api.igdb.com/v4/games/';

        // Create a Guzzle client
        $client = new Client();

        // Forward the request to the external API
        try {
            //get the access token
            $accessTokenResponse = $client->request('POST', $accessTokenUrl, [
                'form_params' => [
                    // Add any required headers here
                    'client_id' => '8sfdd3sko2vfcpqfg5dfhpsogvnj3b',
                    'client_secret' => 'vom6l47mrtaubqfdo6tngjtupntumu',
                    'grant_type' => 'client_credentials',
                   
                ],
            ]);
            $accessTokenData = json_decode($accessTokenResponse->getBody(), true);
            //this is the access token needed for the api
            $accessToken = $accessTokenData['access_token'];
            // dd($accessToken);

            // Api 4 games call 
            $response = $client->request('POST', $externalApiUrl, [
                'headers' => [
                    // Add any required headers here
                    'Accept' => 'application/json',
                    'Client-ID' => '8sfdd3sko2vfcpqfg5dfhpsogvnj3b',
                    'Authorization' => 'Bearer '.$accessToken,
                ],
                // here we are looking for the infos on the game by it's name
                'body' => 'fields release_dates.date, genres.name, websites.category, websites.url, websites.trusted, screenshots.url, cover.url; where name = "'.$game.'"; ',
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data) {
                
                //str_replace to modify the quality of the received picture
                $urlCover = str_replace('thumb', '1080p', $data[0]['cover']['url']);

                // we create an empty array to put the urls insilate later on
                $urlScreenshots = [];
                
                // loop to search the screenshots which will have an id and an url as written in the api doc 
                foreach($data[0]['screenshots'] as $screenshot) {

                    //str_replace to modify the quality of the received picture
                    $urlScreenshot = str_replace('thumb', '1080p', $screenshot['url']);

                    //then we put every screenshot new url in the array urlScreenshots 
                    $urlScreenshots[] = $urlScreenshot;
                }

                
        
                return $this->render('game/gameDetails.html.twig', [

                    //modified url to have a better picture for the game's cover
                    'urlCover' => $urlCover,

                    //modified url to have a better picture for the game's screenshots
                    'urlScreenshots' => $urlScreenshots,

                    //this gives me all the info on the game
                    'infos' => $data,

                    //this gives me the info I entered in the data base
                    'game' => $game,
                ]);
            }else{
                return $this->render('game/gameDetails.html.twig', [

                    //this gives me the info I entered in the data base
                    'game' => $game,
                ]);
            }
            
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

    #[Route('/searchGame/{srch}', name: 'search_game', methods:['GET'])]
    public function searchAGame(GameRepository $gameRepository, Request $request): JsonResponse
    {
        $srch = $request->attributes->get('srch');
        $games = $gameRepository->searchGame($srch);
       
        return  $this->json($games, 200, [], ['groups'=> ['name', 'id']]);
    }

    // #[Route('/countryApi', name: 'country_api')]
    public function displayCountry($cca2)
    {
        // $cca2 = $request->attributes->get('cca2');

        $apiCountries = 'https://restcountries.com/v3.1/alpha/'.$cca2;

        // Create a Guzzle client
        $client = new Client();

        // Api 4 games call 
        $response = $client->request('GET', $apiCountries);

        
        $data = json_decode($response->getBody(), true);
        
        // dd($data[0]);
       
        return $data[0];
    }
}