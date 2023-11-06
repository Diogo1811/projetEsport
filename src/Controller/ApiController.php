<?php 

namespace App\Controller;

use App\Entity\Game;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{
    #[Route('/api-proxy/{name}', name: 'app_api')]
    public function proxyAction(Game $game, Request $request)
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
                    'client_secret' => 'qzwix8527fb8tdsp0bj6qn3811mij7',
                    'grant_type' => 'client_credentials',
                   
                ],
            ]);
            $accessTokenData = json_decode($accessTokenResponse->getBody(), true);
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
                'body' => 'fields release_dates.date, genres.name, websites, alternative_names, cover; where name = "'.$game->getName().'"; ',
            ]);

            $data = json_decode($response->getBody(), true);
            return $this->render('game/gameDetails.html.twig', [
                'games' => $data,
            ]);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }
}