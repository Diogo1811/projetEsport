<?php 

namespace App\Controller;

use App\Entity\Game;
use GuzzleHttp\Client;
use App\Form\TournamentType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/detailsGame/{name}', name: 'details_game')]
    public function gameDetailsApi(Game $game, EntityManagerInterface $entityManager)
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

                if (!$game->isIsVerified()) {

                    $game->setIsVerified(true);

                    // tell Doctrine you want to (eventually) save the game (no queries yet)
                    $entityManager->persist($game);

                    // actually executes the queries (i.e. the INSERT query)
                    $entityManager->flush();
                }
                
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
                    'infos' => $data[0],

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

    public function getApiGames(GameRepository $gameRepository){
        // Twitch token link
        $accessTokenUrl = 'https://id.twitch.tv/oauth2/token';

        // Get the external API URL from the request
        $externalApiUrl = 'https://api.igdb.com/v4/games/';

        // Create a Guzzle client
        $client = new Client();

        $games = $gameRepository->findAll();

        // dd($games->getName());

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

            $apiGames = [];

            foreach ($games as $game) {
                
                // Api 4 games call 
                $response = $client->request('POST', $externalApiUrl, [
                    'headers' => [
    
                        // Add any required headers here
                        'Accept' => 'application/json',
                        'Client-ID' => '8sfdd3sko2vfcpqfg5dfhpsogvnj3b',
                        'Authorization' => 'Bearer '.$accessToken,
    
                    ],
    
                    // here we are looking for the infos on the game by it's name
                    'body' => 'fields cover.url, name; where name = "'.$game->getName().'";',
                ]);
    
                $data = json_decode($response->getBody(), true);
                $apiGames[] = $data[0];
            }
            dd($apiGames);

            if ($data) {

                foreach ($games as $game) {
                    
                    for ($i=0; $i < count($data); $i++) { 

                        if ($data[$i]['name'] == $game->getName()) {

                            dd('ici');
                        
                        }
    
                        //str_replace to modify the quality of the received picture
                        str_replace('thumb', '1080p', $data[$i]['cover']['url']);
                        dd($data);
                    
                    }
                    # code...
                }
                

               
        
                return $this->render('game/gameDetails.html.twig', [

                    //modified url to have a better picture for the game's cover
                    'urlCover' => $urlCover,

                    //this gives me all the info on the game
                    'infos' => $data[0],

                ]);

            }else{

                return $this->render('game/gameDetails.html.twig', [

                   
                ]);
            }
            
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

/********************************* Countries api ************************************************/

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



/************************************************* API TOURNAMENT ****************************************************/
    
    // #[Route('/tournament/tournamentsList', name: 'get_challonge_api')]
    public function getTournaments()
    {

        // This allows me to get a registred tournament
        $apiChallonge = 'https://api.challonge.com/v1/tournaments.json';

        // Create a Guzzle client
        $client = new Client();

        // Api tournament call
        $response = $client->request('GET', $apiChallonge, [
            'query' => [
                'api_key' => 'ywlAxaVHioEqzgZ0uwqzNzU2rpceht45ydKf88fe',
                'state' => 'all'
            ],
        ]);

        
        $data = json_decode($response->getBody(), true);
       
        return $data;
    }

    // Function to find a tournament by is url
    public function findTournamentByUrl($url)
    {

        // This allows me to get a registred tournament
        $apiChallonge = 'https://api.challonge.com/v1/tournaments/'.$url.'.json';

        // Create a Guzzle client
        $client = new Client();

        // Api tournament call
        $response = $client->request('GET', $apiChallonge, [
            'query' => [
                'api_key' => 'ywlAxaVHioEqzgZ0uwqzNzU2rpceht45ydKf88fe',
            ],
        ]);

        
        $data = json_decode($response->getBody(), true);
       
        // dd($data);

        return $data['tournament'];
    }

    
    //add a tournament in the API data base
    public function addTournament($data)
    {

        // This allows me to add a new tournament
        $apiChallonge = 'https://api.challonge.com/v1/tournaments.json';

        // Create a Guzzle client
        $client = new Client();

        // Api tournament call and creation of the tournament
        $client->request('POST', $apiChallonge, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'query' => [
                'api_key' => 'ywlAxaVHioEqzgZ0uwqzNzU2rpceht45ydKf88fe',
            ],
            'body' => $data
        ]);

        return;
        
    }
 /***************************************** PARTICIPANTS *********************************************************/ 

    //add a paticipant to a tournament in the data base
    public function addRosterToTournament($url, $name)
    {

        // This allows me to add a new tournament
        $apiChallonge = 'https://api.challonge.com/v1/tournaments/'.$url.'/participants.json';

        // Create a Guzzle client
        $client = new Client();

        // Api tournament call and creation of the tournament
        $client->request('POST', $apiChallonge, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'query' => [
                'api_key' => 'ywlAxaVHioEqzgZ0uwqzNzU2rpceht45ydKf88fe',
                'participant[name]' => $name
            ]
        ]);

        return;
        
    }

    // Function to find a tournament by is url
    public function findParticipantsByTournamentUrl($url)
    {

        // This allows me to get a registred tournament
        $apiChallonge = 'https://api.challonge.com/v1/tournaments/'.$url.'/participants.json';

        // Create a Guzzle client
        $client = new Client();

        // Api tournament call
        $response = $client->request('GET', $apiChallonge, [
            'query' => [
                'api_key' => 'ywlAxaVHioEqzgZ0uwqzNzU2rpceht45ydKf88fe',
            ],
        ]);

        
        $dataPaticipants = json_decode($response->getBody(), true);

        // dd($dataPaticipants);
        
        $participants = [];
        
        for ($i=0; $i < count($dataPaticipants); $i++) { 
            foreach ($dataPaticipants[$i] as $participant) {
                // dd($participant);
                $participants[] = $participant;
            }
        }
        
        // dd($participants);
        

        return $participants;
    }

    // Function to find a tournament by is url
    public function findParticipantById($url, $id)
    {

        // This allows me to get a registred tournament
        $apiChallonge = 'https://api.challonge.com/v1/tournaments/'.$url.'/participants/'.$id.'.json';

        // Create a Guzzle client
        $client = new Client();

        // Api tournament call
        $response = $client->request('GET', $apiChallonge, [
            'query' => [
                'api_key' => 'ywlAxaVHioEqzgZ0uwqzNzU2rpceht45ydKf88fe',
            ],
        ]);

        
        $dataPaticipant = json_decode($response->getBody(), true);

        // dd($dataPaticipant);
        
        
        
        // dd($participants);
        

        return $dataPaticipant;
    }

 /***************************************** MATCHES ******************************************************************/ 
   
    // Function to find tournament's matches by is url
    public function findMatchesByTournamentUrl($url)
    {

        // This allows me to get a registred tournament
        $apiChallonge = 'https://api.challonge.com/v1/tournaments/'.$url.'/matches.json';

        // Create a Guzzle client
        $client = new Client();

        // Api tournament call
        $response = $client->request('GET', $apiChallonge, [
            'query' => [
                'api_key' => 'ywlAxaVHioEqzgZ0uwqzNzU2rpceht45ydKf88fe',
            ],
        ]);

        
        $dataMatches = json_decode($response->getBody(), true);
        
        $matches = [];
        
        for ($i=0; $i < count($dataMatches); $i++) { 
            foreach ($dataMatches[$i] as $match) {
                // dd($match);
                $matches[] = $match;
            }
        }
        
        // dd($participants);
        

        return $matches;
    }

    // Function to find a tournament's match by is id and tournament url
    public function findMatchById($id, $url)
    {

        // This allows me to get a registred tournament
        $apiChallonge = 'https://api.challonge.com/v1/tournaments/'.$url.'/matches/'.$id.'.json';

        // Create a Guzzle client
        $client = new Client();

        // Api tournament call
        $response = $client->request('GET', $apiChallonge, [
            'query' => [
                'api_key' => 'ywlAxaVHioEqzgZ0uwqzNzU2rpceht45ydKf88fe',
            ],
        ]);

        
        $datas = json_decode($response->getBody(), true);
        
        // dd($datas);
        return $datas['match'];
    }


    // Function to add a score to a match
    public function addScoreToMatch($url, $idMatch, $score, $idWinner)
    {
        $apiChallonge = 'https://api.challonge.com/v1/tournaments/'.$url.'/matches/'.$idMatch.'.json';

        // Create a Guzzle client
        $client = new Client();

        // Api tournament call
        $response = $client->request('PUT', $apiChallonge, [
            'query' => [
                'api_key' => 'ywlAxaVHioEqzgZ0uwqzNzU2rpceht45ydKf88fe',
                'match[scores_csv]' => $score,
                'match[winner_id]' => $idWinner
            ],
        ]);

        return;
    }
}