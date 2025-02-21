<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vinyl;
use App\Models\Track;

class VinylSeeder extends Seeder
{
    public function run()
    {
        $vinyls = [
            [
                'title' => 'Hurry Up Tomorrow',
                'artist' => 'The Weeknd',
                'genre' => 'Electronic, Hip Hop, Funk / Soul, Pop',
                'style' => 'Contemporary R&B, Synth-pop, Synthwave',
                'year' => '2025',
                'label' => 'XO, Republic Records',
                'cover' => 'images/vinyls/hut.png',
                'LP' => 2,
                'feat' => 'Justice, Anitta, Florence + The Machine, Travis Scott, Future, Playboi Carti, Giorgio Moroder, Lana Del Rey',
                'tracks' => [
                    ['track_number' => 1, 'title' => 'Wake Me Up', 'position' => 'A1', 'duration' => '5:08'],
                    ['track_number' => 2, 'title' => 'Cry For Me', 'position' => 'A2', 'duration' => '3:44'],
                    ['track_number' => 3, 'title' => 'I Can\'t Fucking Sing', 'position' => 'A3', 'duration' => '0:12'],
                    ['track_number' => 4, 'title' => 'São Paulo', 'position' => 'A4', 'duration' => '5:01'],
                    ['track_number' => 5, 'title' => 'Until We\'re Skin & Bones', 'position' => 'A5', 'duration' => '0:22'],
                    ['track_number' => 6, 'title' => 'Baptized In Fear', 'position' => 'A6', 'duration' => '3:52'],
                    ['track_number' => 7, 'title' => 'Open Hearts', 'position' => 'A7', 'duration' => '3:54'],
                    ['track_number' => 8, 'title' => 'Opening Night', 'position' => 'B1', 'duration' => '1:36'],
                    ['track_number' => 9, 'title' => 'Reflections Laughing', 'position' => 'B2', 'duration' => '4:51'],
                    ['track_number' => 10, 'title' => 'Enjoy The Show', 'position' => 'B3', 'duration' => '5:01'],
                    ['track_number' => 11, 'title' => 'Given Up On Me', 'position' => 'B4', 'duration' => '5:54'],
                    ['track_number' => 12, 'title' => 'I Can\'t Wait To Get There', 'position' => 'B5', 'duration' => '3:08'],
                    ['track_number' => 13, 'title' => 'Timeless', 'position' => 'C1', 'duration' => '4:16'],
                    ['track_number' => 14, 'title' => 'Niagara Falls', 'position' => 'C2', 'duration' => '4:37'],
                    ['track_number' => 15, 'title' => 'Take Me Back To LA', 'position' => 'C3', 'duration' => '4:13'],
                    ['track_number' => 16, 'title' => 'Big Sleep', 'position' => 'C4', 'duration' => '3:45'],
                    ['track_number' => 17, 'title' => 'Give Me Mercy', 'position' => 'C5', 'duration' => '3:36'],
                    ['track_number' => 18, 'title' => 'Drive', 'position' => 'D1', 'duration' => '3:08'],
                    ['track_number' => 19, 'title' => 'The Abyss', 'position' => 'D2', 'duration' => '4:42'],
                    ['track_number' => 20, 'title' => 'Red Terror', 'position' => 'D3', 'duration' => '3:51'],
                    ['track_number' => 21, 'title' => 'Without a Warning', 'position' => 'D4', 'duration' => '4:57'],
                    ['track_number' => 22, 'title' => 'Hurry Up Tomorrow', 'position' => 'D5', 'duration' => '4:51'],
                ]

            ],
            [
                'title' => 'After Hours',
                'artist' => 'The Weeknd',
                'genre' => 'Electronic, Hip Hop, Funk / Soul, Pop',
                'style' => 'Synthwave, New Wave, Pop Rap, Disco, Contemporary R&B, Synth-pop',
                'year' => '2020',
                'label' => 'XO, Republic Records',
                'cover' => 'images/vinyls/after_hours.png',
                'LP' => 2,
                'feat' => 'None',
                'tracks' => [
                    ['track_number' => 1, 'title' => 'Alone Again', 'position' => 'A1', 'duration' => '4:10'],
                    ['track_number' => 2, 'title' => 'Too Late', 'position' => 'A2', 'duration' => '3:59'],
                    ['track_number' => 3, 'title' => 'Hardest To Love', 'position' => 'A3', 'duration' => '3:31'],
                    ['track_number' => 4, 'title' => 'Scared To Live', 'position' => 'A4', 'duration' => '3:11'],
                    ['track_number' => 5, 'title' => 'Snow Child', 'position' => 'B1', 'duration' => '4:07'],
                    ['track_number' => 6, 'title' => 'Escape From LA', 'position' => 'B2', 'duration' => '5:55'],
                    ['track_number' => 7, 'title' => 'Heartless', 'position' => 'B3', 'duration' => '3:21'],
                    ['track_number' => 8, 'title' => 'Faith', 'position' => 'B4', 'duration' => '4:43'],
                    ['track_number' => 9, 'title' => 'Blinding Lights', 'position' => 'C1', 'duration' => '3:21'],
                    ['track_number' => 10, 'title' => 'In Your Eyes', 'position' => 'C2', 'duration' => '3:57'],
                    ['track_number' => 11, 'title' => 'Save Your Tears', 'position' => 'C3', 'duration' => '3:35'],
                    ['track_number' => 12, 'title' => 'Repeat After Me (Interlude)', 'position' => 'D1', 'duration' => '3:15'],
                    ['track_number' => 13, 'title' => 'After Hours', 'position' => 'D2', 'duration' => '6:01'],
                    ['track_number' => 14, 'title' => 'Until I Bleed Out', 'position' => 'D3', 'duration' => '3:12'],
                ]
            ],
            [
                'title' => '808s & Heartbreak',
                'artist' => 'Kanye West',
                'genre' => 'Electronic, Hip Hop, Funk / Soul, Pop',
                'style' => 'Experimental, Synth-pop, Pop Rap, Contemporary R&B',
                'year' => '2008',
                'label' => 'Roc-A-Fella Records',
                'cover' => 'images/vinyls/808s.png',
                'LP' => 2,
                'feat' => 'Young Jeezy, Lil Wayne, Kid Cudi, Mr Hudson',
                'tracks' => [
                    ['track_number' => 1, 'title' => 'Say You Will', 'position' => 'A1', 'duration' => '6:18'],
                    ['track_number' => 2, 'title' => 'Welcome to Heartbreak', 'position' => 'A2', 'duration' => '4:23'],
                    ['track_number' => 3, 'title' => 'Heartless', 'position' => 'A3', 'duration' => '3:31'],
                    ['track_number' => 4, 'title' => 'Amazing', 'position' => 'B1', 'duration' => '3:58'],
                    ['track_number' => 5, 'title' => 'Love Lockdown', 'position' => 'B2', 'duration' => '4:31'],
                    ['track_number' => 6, 'title' => 'Paranoid', 'position' => 'B3', 'duration' => '4:38'],
                    ['track_number' => 7, 'title' => 'RoboCop', 'position' => 'C1', 'duration' => '4:35'],
                    ['track_number' => 8, 'title' => 'Street Lights', 'position' => 'C2', 'duration' => '3:10'],
                    ['track_number' => 9, 'title' => 'Bad News', 'position' => 'C3', 'duration' => '3:59'],
                    ['track_number' => 10, 'title' => 'See You In My Nightmares', 'position' => 'D1', 'duration' => '4:18'],
                    ['track_number' => 11, 'title' => 'Coldest Winter', 'position' => 'D2', 'duration' => '2:46'],
                    ['track_number' => 12, 'title' => 'Pinocchio Story (Live From Singapore)', 'position' => 'D3', 'duration' => '6:02'],
                ]
            ],
            [
                'title' => 'The College Dropout',
                'artist' => 'Kanye West',
                'genre' => 'Hip-Hop',
                'style' => 'Pop Rap, Conscious, Contemporary R&B',
                'year' => '2004',
                'label' => 'Roc-A-Fella Records, Hustle., Handprint Entertainment',
                'cover' => 'images/vinyls/college_dropout.png',
                'LP' => 2,
                'feat' => 'Jay-Z, J. Ivy, Syleena Johnson, Talib Kweli, Common, Mos Def, Freeway, The Boys Choir of Harlem, Consequence, Ludacris, GLC',
                'tracks' => [
                    ['track_number' => 1, 'title' => 'We Don’t Care', 'position' => 'A1', 'duration' => '3:59'],
                    ['track_number' => 2, 'title' => 'Graduation Day', 'position' => 'A2', 'duration' => '1:22'],
                    ['track_number' => 3, 'title' => 'All Falls Down', 'position' => 'A3', 'duration' => '3:43'],
                    ['track_number' => 4, 'title' => 'Spaceship', 'position' => 'A4', 'duration' => '5:24'],
                    ['track_number' => 5, 'title' => 'Jesus Walks', 'position' => 'A5', 'duration' => '3:13'],
                    ['track_number' => 6, 'title' => 'Never Let Me Down', 'position' => 'B1', 'duration' => '5:24'],
                    ['track_number' => 7, 'title' => 'Get Em High', 'position' => 'B2', 'duration' => '4:49'],
                    ['track_number' => 8, 'title' => 'The New Workout Plan', 'position' => 'B3', 'duration' => '5:22'],
                    ['track_number' => 9, 'title' => 'Through The Wire', 'position' => 'B4', 'duration' => '3:41'],
                    ['track_number' => 10, 'title' => 'Slow Jamz', 'position' => 'C1', 'duration' => '5:16'],
                    ['track_number' => 11, 'title' => 'Breathe In Breathe Out', 'position' => 'C2', 'duration' => '4:06'],
                    ['track_number' => 12, 'title' => 'School Spirit', 'position' => 'C3', 'duration' => '3:02'],
                    ['track_number' => 13, 'title' => 'Two Words', 'position' => 'C4', 'duration' => '4:26'],
                    ['track_number' => 14, 'title' => 'Family Business', 'position' => 'D1', 'duration' => '4:38'],
                    ['track_number' => 15, 'title' => 'Last Call', 'position' => 'D2', 'duration' => '12:40'],
                ]
            ],
            [
                'title' => 'Late Registration',
                'artist' => 'Kanye West',
                'genre' => 'Hip-Hop',
                'style' => 'Conscious, Pop Rap, Contemporary R&B',
                'year' => '2005',
                'label' => 'Roc-A-Fella Records',
                'cover' => 'images/vinyls/late_registration.png',
                'LP' => 2,
                'feat' => 'Jay-Z, Lupe Fiasco, The Game, Consequence, Adam Levine, Jamie Foxx, Glen "Loco" Williams, Syleena Johnson, Lil Wayne, The Boys Choir of Harlem',
                'tracks' => [
                    ['track_number' => 1, 'title' => 'Wake Up Mr. West', 'position' => 'A1', 'duration' => '0:41'],
                    ['track_number' => 2, 'title' => 'Heard \'Em Say (Feat. Adam Levine)', 'position' => 'A2', 'duration' => '3:23'],
                    ['track_number' => 3, 'title' => 'Touch the Sky (Feat. Lupe Fiasco)', 'position' => 'A3', 'duration' => '3:57'],
                    ['track_number' => 4, 'title' => 'Gold Digger (Feat. Jamie Foxx)', 'position' => 'A4', 'duration' => '3:28'],
                    ['track_number' => 5, 'title' => 'Skit #1', 'position' => 'A5', 'duration' => '0:33'],
                    ['track_number' => 6, 'title' => 'Drive Slow (Feat. Paul Wall & GLC)', 'position' => 'A6', 'duration' => '4:32'],
                    ['track_number' => 7, 'title' => 'My Way Home', 'position' => 'B1', 'duration' => '1:43'],
                    ['track_number' => 8, 'title' => 'Crack Music (Feat. The Game)', 'position' => 'B2', 'duration' => '4:31'],
                    ['track_number' => 9, 'title' => 'Roses', 'position' => 'B3', 'duration' => '4:05'],
                    ['track_number' => 10, 'title' => 'Bring Me Down (Feat. Brandy)', 'position' => 'B4', 'duration' => '3:18'],
                    ['track_number' => 11, 'title' => 'Addiction', 'position' => 'C1', 'duration' => '4:27'],
                    ['track_number' => 12, 'title' => 'Skit #2', 'position' => 'C2', 'duration' => '0:31'],
                    ['track_number' => 13, 'title' => 'Diamonds from Sierra Leone (Feat. Jay-Z) [remix]', 'position' => 'C3', 'duration' => '3:53'],
                    ['track_number' => 14, 'title' => 'We Major (Feat. Nas and Really Doe)', 'position' => 'C4', 'duration' => '7:28'],
                    ['track_number' => 15, 'title' => 'Skit #3', 'position' => 'D1', 'duration' => '0:24'],
                    ['track_number' => 16, 'title' => 'Hey Mama', 'position' => 'D2', 'duration' => '5:05'],
                    ['track_number' => 17, 'title' => 'Celebration', 'position' => 'D3', 'duration' => '3:18'],
                    ['track_number' => 18, 'title' => 'Skit #4', 'position' => 'D4', 'duration' => '1:18'],
                    ['track_number' => 19, 'title' => 'Gone (Feat. Consequence & Cam\'Ron)', 'position' => 'D5', 'duration' => '5:33'],
                ]
            ],
            [
                'title' => 'My Beautiful Dark Twisted Fantasy',
                'artist' => 'Kanye West',
                'genre' => 'Hip-Hop',
                'style' => 'Contemporary R&B, Pop Rap, Avantgarde',
                'year' => '2010',
                'label' => 'Roc-A-Fella Records',
                'cover' => 'images/vinyls/mbdtf.png',
                'LP' => 3,
                'feat' => 'Jay-Z, Kid Cudi, Pusha T, Rick Ross, Bon Iver, Raekwon, Nicki Minaj, Elton John, Mike Dean, The-Dream, Alicia Keys, Fergie, Consequence, CyHi the Prynce',
                'tracks' => [
                    ['track_number' => 1, 'title' => 'Dark Fantasy', 'position' => 'A1', 'duration' => '4:40'],
                    ['track_number' => 2, 'title' => 'Gorgeous', 'position' => 'A2', 'duration' => '5:57'],
                    ['track_number' => 3, 'title' => 'Power', 'position' => 'B1', 'duration' => '4:52'],
                    ['track_number' => 4, 'title' => 'All Of The Lights (Interlude)', 'position' => 'B2', 'duration' => '1:02'],
                    ['track_number' => 5, 'title' => 'All Of The Lights', 'position' => 'B3', 'duration' => '4:59'],
                    ['track_number' => 6, 'title' => 'Monster', 'position' => 'C', 'duration' => '6:18'],
                    ['track_number' => 7, 'title' => 'So Appalled', 'position' => 'D1', 'duration' => '6:38'],
                    ['track_number' => 8, 'title' => 'Devil In A New Dress', 'position' => 'D2', 'duration' => '5:52'],
                    ['track_number' => 9, 'title' => 'Runaway', 'position' => 'E1', 'duration' => '9:08'],
                    ['track_number' => 10, 'title' => 'Hell Of A Life', 'position' => 'E2', 'duration' => '5:27'],
                    ['track_number' => 11, 'title' => 'Blame Game', 'position' => 'F1', 'duration' => '7:49'],
                    ['track_number' => 12, 'title' => 'Lost In The World', 'position' => 'F2', 'duration' => '4:16'],
                    ['track_number' => 13, 'title' => 'Who Will Survive In America', 'position' => 'F3', 'duration' => '1:38'],
                ]
            ]
        ];

        foreach ($vinyls as $vinylData) {
            // creates all added vinyls
            $vinyl = Vinyl::create([
                'title' => $vinylData['title'],
                'artist' => $vinylData['artist'],
                'genre' => $vinylData['genre'],
                'style' => $vinylData['style'],
                'year' => $vinylData['year'],
                'label' => $vinylData['label'],
                'cover' => $vinylData['cover'],
                'LP' => $vinylData['LP'],
                'feat' => $vinylData['feat'],
            ]);

            // adds tracks to each vinyl
            $vinyl->tracks()->createMany($vinylData['tracks']);
        }
    }
}
