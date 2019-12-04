<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AdminLoginController extends Controller
{
    public function __construct(){
        $this->middleware('guest:admin',['except' => 'adminLogout']);
    }
    public function showLoginForm()
    {
    	return view('auth.admin_login');
    }

    public function login(Request $request)
    {
        //validate the form data 
        $this->validate($request,[
             'email'    => 'required|email',
             'password' => 'required|min:6'
        ]);
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password
        ];

         //attempt to log the user in
         if(Auth::guard('admin')->attempt($credentials,$request->remember)){

             //if successfull, then redirect to their intended location
              return redirect()->intended(route('admin.dashboard'));
         }

        //if unsuccessfull,then redirect to the login with the form data 
        return redirect()->back()->withInput($request->only('email','remember'));
    }

    public function adminLogout()
    {
        Auth::guard('admin')->logout();
       return redirect('/');
    }
}
