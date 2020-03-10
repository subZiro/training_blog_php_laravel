<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // метод открытия станицы польхователей
        $users = User::all();
        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // метод открытия страницы создание категории
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //метод создания категории (добавление в бд)
        $this->validate($request, [
            'name' => 'required',  // поле name обязательно к заполнению
            'avatar' => 'image|nullable',  // поле avatar изображение или пустое
            'email' => 'required|email|unique:users',  // поле email валидация на email уникальность по пользователям
            'password' => 'required|string|min:6',  // поле password обязательно к заполнению
        ]);
        
        $user = User::add($request->all());
        $user->uploadAvatar($request->file('avatar'));
        return redirect()->route('users.index');  // возврат к странице со всеми пользователми
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // перенаправление на страницу редактирования пользователя
        $user = User::find($id);
        return view('admin.users.edit', compact('user'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, $id)
    {
        // редактирование пользователя
        $user = User::find($id);
        $this->validate($request, [
            'name' => 'required',
            'avatar' => 'nullable|image',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ]
        ]);

        $oldpassword = $user->password;
        $user->edit($request->all()); //name,email
        $user->generatePassword($request->get('password'), $oldpassword);
        $user->uploadAvatar($request->file('avatar'));  //upload avatar

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // удаление пользователя
        $users = User::find($id)->remove();
        return redirect()->route('users.index');  // возврат к пользователям
    }
}
