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
    public function explore(Request $request) {
        $vinyls = Vinyl::all();
        return view('explore', compact('vinyls'));
    }

    public function exploreSearch(Request $request){

        $query = $request->input('q');
        $vinyls = Vinyl::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('title', 'like', "%{$query}%")
                                ->orWhere('artist', 'like', "%{$query}%");
        })->get();
        return view('explore', compact('vinyls', 'query'));
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
