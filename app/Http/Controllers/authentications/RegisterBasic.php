<?php

namespace App\Http\Controllers\authentications;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-register-basic');
  }

  public function store(Request $request)
    {
     
      
        // Validate the incoming request   data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
          dd($validator->errors());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        
        

       
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => 'A'
        ]);

        // Log the user in or redirect them
        auth()->login($user);

        // Redirect to a desired page after successful registration
        return redirect()->route('dashboard-analytics')->with('success', 'Registration successful!');


    }
}
