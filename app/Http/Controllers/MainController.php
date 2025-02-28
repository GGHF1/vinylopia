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

    public function marketplace() {
        $vinyls = Vinyl::all();
        return view('marketplace', compact('vinyls'));
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
