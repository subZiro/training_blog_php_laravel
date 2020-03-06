<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
    	// страница пользователя
    	$user = Auth::user();
    	return view('pages.profile', ['user'=>$user]);
    }

    public function store(Request $request)
    {
    	// отправка изменений в бд
        $this->validate($request, [
            'name' => 'required',  // поле name обязательно к заполнению
            'avatar' => 'image|nullable',  // поле avatar изображение или пустое
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(Auth::user()->id),
            ]
        ]);

        $user = Auth::user();
		$oldpassword = $user->password;

        $user->uploadAvatar($request->file('avatar'));  //upload avatar
        $user->edit($request->all()); //name,email
        $user->generatePassword($request->get('password'), $oldpassword);

        return redirect()->back()->with('status', 'Данные успешно обновлены!'); 
    }
}
