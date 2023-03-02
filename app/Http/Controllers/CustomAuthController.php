<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomAuthController extends Controller
{
    public function index() {
        return view('auth.login');
    }
    public function customLogin (Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('dashboard');
        }
        return back()->with('error', 'Invalid login details');
    }

     public function registration() {
        return view('auth.registeration');
     }

     public function customRegistration(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        
        $data = $request->all();
        $check = $this->create($data);
        return redirect()->route('login')->with('success', 'Registered successfully');
     }

     public function create(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
     }

        public function dashboard() {
            if (auth()->check()) {
                return view('dashboard');
            }
            return redirect()->route('login')->with('error', 'You are not allowed to access');
        }
    
        public function signOut() {
            Session::flush();
            Auth::logout();
            return Redirect('login');
        }
        
    
}