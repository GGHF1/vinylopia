<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ITunesService{

    public function getiTunesUrl($albumName, $artistName){
        $query = urlencode("$albumName $artistName");
        $url = "https://itunes.apple.com/search?term=$query&entity=album&limit=1";
        
        $response = Http::get($url);
        $data = $response->json();

        if(isset($data['results'][0])){
            return $data['results'][0]['collectionViewUrl'];
        }
        return null;
    }
}