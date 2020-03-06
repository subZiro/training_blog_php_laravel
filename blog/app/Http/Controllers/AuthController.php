<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registrationForm()
    {
    	// форма регистрации
    	return view('auth.registration');
    }

    public function registration(Request $request)
    {
    	// регистрация пользователя
		$this->validate($request, [
			'name' => 'required',  // поле name обязательно к заполнению
			'password' => 'required',  // поле password обязательно к заполнению
			'email' => 'required|email|unique:users'  // поле email валидация на email уникальность по пользователям
		]);

		// создание пользователя в бд
		$user = User::add($request->all());
        $user->generatePassword($request->get('password'));

        return redirect('/login');

    }

    public function loginForm()
    {
    	// форма входа
    	return view('auth.login');
    }

    public function login(Request $request)
    {
    	// вход
    	$this->validate($request, [
    		'password' => 'required',  // поле password обязательно к заполнению
			'email' => 'required|email'  // поле email валидация на email уникальность по пользователям
		]);

    	if(Auth::attempt([
    			'email' => $request->get('email'),
    			'password' => $request->get('password')
    		]))
		{
			return redirect('/');	
		}
		return redirect()->back()->with('status', 'Неправильный логин и/или пароль');
    }

    public function logout()
    {
        // выход
        Auth::logout();
        return redirect('/login');
    }
    

}

