<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Vinyl;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecondIterationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test search bar returns results for existing vinyl.
     */
    public function test_search_bar_returns_results_for_existing_vinyl(): void
    {
        // Create a vinyl record in the database
        Vinyl::create([
            'title' => 'Parachutes',
            'artist' => 'Coldplay',
            'genre' => 'Rock',
            'style' => 'Alternative Rock, Pop Rock',
            'year' => '2000',
            'label' => 'Parlophone, Parlophone',
            'barcode' => '724352778317',
            'release_id' => 484030,
            'cover' => 'https://i.discogs.com/qX8Ho7VAkk8gW-rKITdc4cGcfaEFR4ouGsQ-aCJ64og/rs:fit/g:sm/q:90/h:600/w:600/czM6Ly9kaXNjb2dz/LWRhdGFiYXNlLWlt/YWdlcy9SLTQ4NDAz/MC0xMTY0NzQ3Nzg4/LmpwZWc.jpeg',
            'secondary_cover' => '["https:\/\/i.discogs.com\/48a6RhiDKTy9omj2rJ78Oeim9K33omP8iGrpuW0wpC0\/rs:fit\/g:sm\/q:90\/h:585\/w:589\/czM6Ly9kaXNjb2dz\/LWRhdGFiYXNlLWlt\/YWdlcy9SLTQ4NDAz\/MC0xMjQ4ODAyNDM3\/LmpwZWc.jpeg","https:\/\/i.discogs.com\/ZeTm5xmzqheMMwsdXTRvGQu8Z6nTlnTyBYwk3XkaS2c\/rs:fit\/g:sm\/q:90\/h:595\/w:596\/czM6Ly9kaXNjb2dz\/LWRhdGFiYXNlLWlt\/YWdlcy9SLTQ4NDAz\/MC0xMjQ4ODAyNTk2\/LmpwZWc.jpeg","https:\/\/i.discogs.com\/j7ud2n5hYx2FpQwOJLfLC2xOWGOUQEcAOFYrmfA_y5c\/rs:fit\/g:sm\/q:90\/h:583\/w:581\/czM6Ly9kaXNjb2dz\/LWRhdGFiYXNlLWlt\/YWdlcy9SLTQ4NDAz\/MC0xMjQ4ODAyNjIx\/LmpwZWc.jpeg","https:\/\/i.discogs.com\/4n8yw2GaRvKr0uXFCFhdEMR1GNXeN7WeuIHZh-7oGoI\/rs:fit\/g:sm\/q:90\/h:584\/w:586\/czM6Ly9kaXNjb2dz\/LWRhdGFiYXNlLWlt\/YWdlcy9SLTQ4NDAz\/MC0xMjQ4ODAyNDc5\/LmpwZWc.jpeg","https:\/\/i.discogs.com\/jv8t33uzn0Ci3l4KE83SyXmu_oxSa2Z44ElWqqgh1kY\/rs:fit\/g:sm\/q:90\/h:593\/w:597\/czM6Ly9kaXNjb2dz\/LWRhdGFiYXNlLWlt\/YWdlcy9SLTQ4NDAz\/MC0xMjQ4ODAyNTU4\/LmpwZWc.jpeg","https:\/\/i.discogs.com\/TgHHPHZHxJwvIDZpP9MrAMI1-vncWG_-jfi1XZc0EaA\/rs:fit\/g:sm\/q:90\/h:153\/w:600\/czM6Ly9kaXNjb2dz\/LWRhdGFiYXNlLWlt\/YWdlcy9SLTQ4NDAz\/MC0xNzMxMDgyOTI4\/LTcyODYuanBlZw.jpeg"]',
            'format' => '1 LP, Album',
            'feat' => 'None',
            'spotify_link' => 'https://open.spotify.com/album/6ZG5lRT77aJ3btmArcykra',
            'itunes_link' => 'https://music.apple.com/us/album/parachutes/1122782080?uo=4',
        ]);

        // Perform a search for the vinyl
        $response = $this->get('/explore/search?q=Parachutes');

        // Assert that the response contains the vinyl record
        $response->assertStatus(200);
        $response->assertSee('Parachutes');
        $response->assertSee('Coldplay');
    }

    /**
     * Test search bar returns no results for non-existing vinyl.
     */
    public function test_search_bar_returns_no_results_for_non_existing_vinyl(): void
    {
        $response = $this->get('/explore/search?q=NonExistentVinyl');

        // Assert that the response contains the search query and no results message
        $response->assertStatus(200);
        $response->assertSeeText('NonExistentVinyl');
       
    }
}