<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vinyl;
use App\Models\Listing;

class MainController extends Controller
{
    public function home() {
        return view('home');
    }

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
    
    public function marketplace(Request $request)
    {
        $sort = $request->input('sort');
        $genre = $request->input('genre');
        $artist = $request->input('artist');
        $year = $request->input('year');
        $condition = $request->input('condition');

        $query = Listing::query();
        
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

        // sort options
        if ($sort === 'price_asc') {
            $query->orderBy('listings.price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('listings.price', 'desc');
        } elseif ($sort === 'date_desc') {
            $query->orderBy('listings.created_at', 'desc');
        } elseif ($sort === 'date_asc') {
            $query->orderBy('listings.created_at', 'asc');
        }
    
        $listings = $query->with(['vinyl', 'user'])->paginate(10)->withQueryString();
        
        $genres = Vinyl::distinct()->pluck('genre');
        $artists = Vinyl::distinct()->pluck('artist');
        $years = Vinyl::distinct()->pluck('year');
        
        $vinylRelease = Vinyl::select('vinyl_id', 'barcode')->get();
    
        return view('marketplace', compact('listings', 'genres', 'artists', 'years', 'vinylRelease'));
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

    public function create(Request $request)
    {
        $vinylId = $request->input('vinyl_id');
        $preSelectedVinyl = null;
        
        if ($vinylId) {
            $preSelectedVinyl = Vinyl::find($vinylId);
        }
        
        return view('listings', compact('preSelectedVinyl'));
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        $field = $request->input('field');
        
        if (strlen($query) < 3) {
            return response()->json([]);
        }
        
        $vinyls = Vinyl::query();
        
        switch ($field) {
            case 'artist':
                $vinyls->where('artist', 'LIKE', "%{$query}%");
                break;
            case 'title':
                $vinyls->where('title', 'LIKE', "%{$query}%");
                break;
            case 'year':
                $vinyls->where('year', 'LIKE', "%{$query}%");
                break;
            case 'barcode':
                $vinyls->where('barcode', 'LIKE', "%{$query}%");
                break;
            default:
                $vinyls->where(function($q) use ($query) {
                    $q->where('artist', 'LIKE', "%{$query}%")
                      ->orWhere('title', 'LIKE', "%{$query}%")
                      ->orWhere('year', 'LIKE', "%{$query}%")
                      ->orWhere('barcode', 'LIKE', "%{$query}%");
                });
        }
        
        $results = $vinyls->get(['vinyl_id', 'artist', 'title', 'year', 'cover', 'barcode']);
        
        return response()->json($results);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'vinyl_id' => 'required|exists:vinyls,vinyl_id',
            'price' => 'required|numeric|min:0.01',
            'vinyl_condition' => 'required|in:Mint,Near Mint,Very Good Plus,Very Good,Good/Good Plus,Poor/Fair',
            'cover_condition' => 'required|in:Mint,Near Mint,Very Good Plus,Very Good,Good/Good Plus,Poor/Fair',
            'comments' => 'nullable|string|max:1000',
        ]);
        
        $listing = new Listing();
        $listing->vinyl_id = $request->vinyl_id;
        $listing->user_id = Auth::id();
        $listing->price = $request->price;
        $listing->vinyl_condition = $request->vinyl_condition;
        $listing->cover_condition = $request->cover_condition;
        $listing->comments = $request->comments;
        $listing->save();
        
        return redirect()->route('marketplace');
    }

}
