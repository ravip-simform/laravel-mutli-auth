<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function authenticate(Request $req){
        $this->validate($req,[
            "email"     => "required|email",
            "password"  => "required"
        ]);

        if(Auth::guard('admin')->attempt(['email' => $req->email, 'password' => $req->password])){
            return redirect()->route("admin.dashboard");
        }else{
            session()->flash("login_error","Email or Password is incorrect. Please try again.");
            //return redirect()->route("admin.login");
            return back()->withInput($req->only("email"));
        }
    }

    public function logout(){
        Auth::guard("admin")->logout();
        return redirect()->route("admin.login");
    }
}
