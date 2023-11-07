<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\In;
use App\Models\User;
use Auth;
use Session;
use Validator;
use Hash;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }

    public function actionLogin(Request $request){
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        $isValid = Validator::make($request->all(),$rules);

        if($isValid->fails()){
            return redirect()->back()->withErrors($isValid->errors());
        }else{
            $data = [
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ];
            if (Auth::Attempt($data)) {
                return redirect('/home');
            }else{
                Session::flash('error', 'Email atau Password Salah');
                return redirect('/');
            }
        }
    }

    public function createUser(){
        $user = new User;
        $user->name = 'Admin';
        $user->email = 'admin@jackson-corner.fun';
        $user->password = Hash::make('admin123#');
        $user->save();
    }

    public function actionlogout()
    {
        Auth::logout();
        return redirect('/');
    }
}
