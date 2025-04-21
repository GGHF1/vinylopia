<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vinyl;

class MainController extends Controller
{
    public function home() {
        return view('home');
    }

    // Logic will be changed soon
    // Explore - all vinyls released
    // Marketplace - all vinyls for sale by users
    public function explore(Request $request){
        $sort = $request->input('sort');
        $genre = $request->input('genre');
        $artist = $request->input('artist');
        $year = $request->input('year');

        $query = Vinyl::query();

        // sort options
        if ($sort === 'title') {
            $query->orderBy('title', 'asc');
        } elseif ($sort === 'artist') {
            $query->orderBy('artist', 'asc');
        } elseif ($sort === 'year') {
            $query->orderBy('year', 'desc');
        }

        // filter options
        if ($genre) {
            $query->where('genre', $genre);
        }
        if ($artist) {
            $query->where('artist', $artist);
        }
        if ($year) {
            $query->where('year', $year);
        }

        $genres = Vinyl::select('genre')->distinct()->pluck('genre');
        $artists = Vinyl::select('artist')->distinct()->pluck('artist');
        $years = Vinyl::select('year')->distinct()->pluck('year');

        $vinyls = $query->paginate(10)->withQueryString();

        return view('explore', compact('vinyls', 'sort', 'genres', 'artists', 'years'));
    }

    public function exploreSearch(Request $request){
        $query = $request->input('q');

        $vinyls = Vinyl::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('title', 'like', "%{$query}%")
                                ->orWhere('artist', 'like', "%{$query}%");
        })->paginate(10)->withQueryString();

        $genres = Vinyl::select('genre')->distinct()->pluck('genre');
        $artists = Vinyl::select('artist')->distinct()->pluck('artist');
        $years = Vinyl::select('year')->distinct()->pluck('year');

        return view('explore', compact('vinyls', 'query', 'genres', 'artists', 'years'));
    }


    public function marketplace() {
        $vinyls = Vinyl::all();
        return view('explore', compact('vinyls'));
    }

    public function vinylrelease($vinyl_id)
    {
        $vinyl = Vinyl::with('tracks')->findOrFail($vinyl_id);
        return view('release', compact('vinyl'));
    }

    public function artist()
    {
        return view('artist');
    }

}
