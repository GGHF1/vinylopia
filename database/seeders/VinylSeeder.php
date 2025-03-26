<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vinyl;
use App\Models\Track;
use Illuminate\Support\Facades\Http;
use App\Services\SpotifyService;
use App\Services\iTunesService;

class VinylSeeder extends Seeder
{
    protected $spotifyService;
    protected $iTunesService;

    public function __construct(SpotifyService $spotifyService, iTunesService $iTunesService)
    {
        $this->spotifyService = $spotifyService;
        $this->iTunesService = $iTunesService;
    }

    public function run()
    {
        $vinyls = [
            
            ['release_id' => '31902511'],
            ['release_id' => '484030'],
            ['release_id' => '28993519'],
            ['release_id' => '15961158'],
            ['release_id' => '5709533'],
            ['release_id' => '12773291'],
            ['release_id' => '1044164'],
            ['release_id' => '233445'],
            ['release_id' => '7810100'],
            ['release_id' => '11208487'],
            ['release_id' => '2606952'],
            ['release_id' => '7266689'],
            ['release_id' => '28970116'],
            ['release_id' => '12511980'],
            ['release_id' => '9403008'],
            ['release_id' => '20587606'],
            ['release_id' => '9778270'],
            ['release_id' => '7435327'],
            ['release_id' => '530085'],
            ['release_id' => '30423881'],
            ['release_id' => '15968171'],
            ['release_id' => '27840414'],
            ['release_id' => '12802012'],
            ['release_id' => '23000420'],
            ['release_id' => '1608688'],
            ['release_id' => '33007125'],
            ['release_id' => '9258642'],
            ['release_id' => '3354383'], 
            
        ];

        foreach ($vinyls as $vinylData) {

            if (Vinyl::where('release_id', $vinylData['release_id'])->exists()){
                continue; // checks if vinyl already exists. If so, skips to the next one
            }
            
            $getInfo = $this->getVinylInfo($vinylData['release_id']);
            $spotifyLink = $this->spotifyService->getAlbumUrl($getInfo['title'], $getInfo['artist']);
            $iTunesLink = $this->iTunesService->getiTunesUrl($getInfo['title'], $getInfo['artist']);
            $vinylData['artist'] = $getInfo['artist'];
            $vinylData['title'] = $getInfo['title'];
            $vinylData['genre'] = $getInfo['genre'];
            $vinylData['style'] = $getInfo['style'];
            $vinylData['year'] = $getInfo['year'];
            $vinylData['label'] = $getInfo['label'];
            $vinylData['barcode'] = $getInfo['barcode'];
            $vinylData['format'] = $getInfo['format'];
            $vinylData['feat'] = $getInfo['feat'];
            $vinylData['tracks'] = $getInfo['tracks'];
            $vinylData['cover'] = $getInfo['primary'];
            $vinylData['secondary_cover'] = $getInfo['secondary'];
            $vinylData['spotify_link'] = $spotifyLink;
            $vinylData['itunes_link'] = $iTunesLink;
            

            // creates all added vinyls
            $vinyl = Vinyl::create([
                'title' => $vinylData['title'],
                'artist' => $vinylData['artist'],
                'genre' => $vinylData['genre'],
                'style' => $vinylData['style'],
                'year' => $vinylData['year'],
                'label' => $vinylData['label'],
                'barcode' => $vinylData['barcode'],
                'release_id' => $vinylData['release_id'],
                'cover' => $vinylData['cover'],
                'secondary_cover' => $vinylData['secondary_cover'],
                'format' => $vinylData['format'],
                'feat' => $vinylData['feat'],
                'spotify_link' => $vinylData['spotify_link'],
                'itunes_link' => $vinylData['itunes_link'],

            ]);

            // adds tracks to each vinyl
            $vinyl->tracks()->createMany($vinylData['tracks']);
            sleep(0.2);
        }
    }

    private function getVinylInfo($releaseId){
        
        $response = Http::get("https://api.discogs.com/releases/{$releaseId}");

        if ($response->successful()) {
            $result = $response->json();

            // barcode (scanned)
            $barcode = null;
            if (!empty($result['identifiers'])) {
                foreach ($result['identifiers'] as $identifier) {
                    if ($identifier['type'] === 'Barcode') {
                        $barcode = str_replace(' ', '', $identifier['value']);
                        break;
                    }
                }
            }

            // vinyl format
            $formats_arr = [];
            if (!empty($result['formats'])) {
                foreach ($result['formats'] as $formats) {
                    if ($formats['name'] === 'Vinyl') {
                        $formatString = $formats['qty'] . ' ' . implode(', ', $formats['descriptions']);
                        if (!empty($formats['text'])) {
                            $formatString .= ', ' . $formats['text'];
                        }
                        $formats_arr[] = $formatString;
                    }
                }
            }
            $format = implode("\n", $formats_arr);
    

            // tracklist and featuring artists
            $tracks = [];
            $featuringArtists = [];
            // if main artist is included in tracklist artists, remove it
            $mainArtist = !empty($result['artists'][0]['name']) ? preg_replace('/\s*\(\d+\)$/', '', $result['artists'][0]['name']) : '';

            if (!empty($result['tracklist'])) {
                foreach ($result['tracklist'] as $track) {
                    if (!empty($track['artists'])) {
                        foreach ($track['artists'] as $artist) {
                            // some vinyls stores artists with parenthesis and number
                            // $cleanName needed to remove parenthesis after artist name
                            $cleanName = preg_replace('/\s*\(\d+\)$/', '', $artist['name']); 
                            if ($cleanName !== $mainArtist) { 
                                $featuringArtists[] = $cleanName;
                            }
                        }
                    }
                    
                    if (!empty($track['extraartists'])) {
                        foreach ($track['extraartists'] as $extraArtist) {
                            if (isset($extraArtist['role']) && stripos($extraArtist['role'], 'Featuring') === 0) {
                                $cleanName = preg_replace('/\s*\(\d+\)$/', '', $extraArtist['name']);
                                if ($cleanName !== $mainArtist) { 
                                    $featuringArtists[] = $cleanName;
                                }
                            }
                        }
                    }

                    $tracks[] = [
                        'title' => $track['title'],
                        'position' => $track['position'] ?? null,
                        'track_number' => count($tracks) + 1,
                    ];
                }
            }

            $result = $response->json();
            $primaryImage = null;
            $secondaryImages = [];

            if (isset($result['images'])) {
                foreach ($result['images'] as $image) {
                    if ($image['type'] === 'primary' && !$primaryImage) {
                        $primaryImage = $image['uri'];
                    } elseif ($image['type'] === 'secondary') {
                        $secondaryImages[] = $image['uri'];
                    }
                }
                if (!$primaryImage && !empty($secondaryImages)) {
                    $primaryImage = array_shift($secondaryImages);
                }
            }

            // remove duplicate featuring artists
            $featuringArtists = array_unique($featuringArtists);
            $feat = !empty($featuringArtists) ? implode(', ', $featuringArtists) : 'None';

            return [
                'artist' => preg_replace('/\s*\(\d+\)$/', '', $result['artists'][0]['name']) ?? 'Unknown Artist',
                'title' => $result['title'] ?? 'Unknown Title',
                'genre' => implode(', ', $result['genres'] ?? []),
                'style' => implode(', ', $result['styles'] ?? []),
                'year' => $result['year'] ?? null,
                'label' => implode(', ', array_column($result['labels'], 'name') ?? []),
                'barcode' => $barcode,
                'format' => $format,
                'feat' => $feat,
                'tracks' => $tracks, 
                'primary' => $primaryImage,
                'secondary' => json_encode($secondaryImages),
            ];
        }

        return [
            'artist' => 'Unknown Artist',
            'title' => 'Unknown Title',
            'genre' => 'Unknown',
            'style' => 'Unknown',
            'year' => 'Unknown year',
            'label' => 'Unknown',
            'barcode' => null,
            'format' => null,
            'feat' => 'None',
            'tracks' => [],
            'primary' => null,
            'secondary' => json_encode([]),
        ];
    }
}
