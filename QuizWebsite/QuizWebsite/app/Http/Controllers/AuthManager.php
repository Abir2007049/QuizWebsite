<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
class AuthManager extends Controller
{
    
    // public function teacher()
    // {
    //     return view('Teacher');
    // }

    public function registration()
    {
        return view('Registration');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        //  {
        //     $products = Product::all();
        //     $femaleProducts = Femproduct::all();
        //    
        // }
        if (Auth::attempt($credentials))
        return view('Teacher');
    }

    public function registrationPost(Request $request)
    {
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
       // $data['cell'] = $request->cell;
        $user = User::create($data);
        if (!$user) {
            return redirect(route('TorS'))->with("error", "Try Again!");
        }
    
        return view('Teacher');
    }
}