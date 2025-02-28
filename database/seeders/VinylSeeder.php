<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vinyl;
use App\Models\Track;
use Illuminate\Support\Facades\Http;

class VinylSeeder extends Seeder
{
    public function run()
    {
        $vinyls = [
            ['release_id' => '33007125'],
            ['release_id' => '15961158'],
            ['release_id' => '1608688'],
            ['release_id' => '28970116'],
            ['release_id' => '233445'],
            ['release_id' => '530085'],
            ['release_id' => '2606952'],
            ['release_id' => '28993519'],
            ['release_id' => '15968171']
        ];

        foreach ($vinyls as $vinylData) {

            $getInfo = $this->getVinylInfo($vinylData['release_id']);

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
            ]);

            // adds tracks to each vinyl
            $vinyl->tracks()->createMany($vinylData['tracks']);
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
                'artist' => $result['artists'][0]['name'] ?? 'Unknown Artist',
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
            'year' => null,
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
