<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    // Sign Up section

    public function signupForm(){
        $countries = Country::all();
        return view('signup', compact('countries'));
    }

    public function signup(Request $request){
        $request->validate([
            'fname' => ['required', 'regex:/^[A-Za-z]+$/'],
            'lname' => ['required', 'regex:/^[A-Za-z]+$/'],
            'email' => ['required', 'email', 'unique:users,email'],
            'username' => ['required', 'regex:/^[a-zA-Z0-9]+$/', 'unique:users,username'],
            'password' => [
                'required',
                'confirmed', 
                'min:8', 
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'address' => 'required|string|min:10',
            'country_id' => 'required|exists:countries,country_id', 
        ], [
            'fname.regex' => 'First name must contain only letters.',
            'lname.regex' => 'Last name must contain only letters.',
            'username.regex' => 'Username must contain only letters and digits.',
            'password.regex' => 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'address.required' => 'Please enter your shipping address.',
            'address.min' => 'Shipping address must be at least 10 characters long.'
        ]);

        $avatarPath = null;
        $user = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'avatar' => $avatarPath,
            'address' => $request->address,
            'country_id' => $request->country_id,
        ]);

        event(new Registered($user));
        Auth::login($user);
        return redirect('/');
    }

    // Sign In section

    public function loginForm(){
        return view('login');
    }

    public function login(Request $request) {
        $credentials = $request->only('username', 'password');
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        } else {
            return redirect('/login')->with('error', 'Invalid credentials');
        }
    }

    public function profile(){
        Auth::user();
        return view('profile');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();  // clears all session data
        $request->session()->regenerateToken();  // regenerates the CSRF token
        return redirect('/');
    }

    public function uploadAvatar(Request $request){
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        if ($request->hasFile('avatar')) {
            
            $path = $request->file('avatar')->store('avatars', 'public');  // public disk in filesystems.php
        
            $user->avatar = 'avatars/' . basename($path);
            /** @var \App\Models\User $user **/ // added notation to avoid error "undefined method 'save'
            $user->save(); 
        }

        return redirect()->route('profile'); 
    }

    public function deleteAvatar(){
        $user = Auth::user();
        if ($user->avatar) {
            $avatarPath = storage_path('app/public/' . $user->avatar);

            if (file_exists($avatarPath)) {
                unlink($avatarPath);
            }

            $user->avatar = null; // set to default png
            /** @var \App\Models\User $user */ 
            $user->save();
        }

        return redirect()->route('profile')->with('success', 'Avatar deleted successfully.');
    }
}
