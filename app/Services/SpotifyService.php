<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SpotifyService
{
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {   
        // Add Spotify Client ID and Client Secret to .env file. Read README.md file for more information.
        $this->clientId = env('SPOTIFY_CLIENT_ID');
        $this->clientSecret = env('SPOTIFY_CLIENT_SECRET');
    }

    public function getAccessToken()
    {
        // I use Spotify Client Credentials Flow
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                        ->asForm()
                        ->post('https://accounts.spotify.com/api/token', [
                            'grant_type' => 'client_credentials',
                        ]);

        return $response->json()['access_token'];
    }

    public function getAlbumUrl($albumName, $artistName)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
                        ->get('https://api.spotify.com/v1/search', [
                            'q' => $albumName . ' ' . $artistName,
                            'type' => 'album',
                            'limit' => 1, 
                        ]);

        $data = $response->json();

        if (isset($data['albums']['items'][0])) {
            return $data['albums']['items'][0]['external_urls']['spotify'];
        }

        return null; 
    }
}
