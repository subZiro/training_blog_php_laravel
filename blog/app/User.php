<?php

namespace App;

use \Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public static function add($fields)
    {   
        //добавление пользователя
        $user = new static;
        $user->fill($fields);
        $user->save();

        return $user;
    }

    public function edit($fields)                   
    {
        // изменение пользователя
        $this->fill($fields);  // name, email
        $this->save();
    }

    public function generatePassword($password)
    {
        // метод для шифрования пароля
        if($password != null)
        {
            $this->password = bcrypt($password);
            $this->save();
        }
    }

    public function remove()
    {
        // удаление аватара
        $this->removeAvatar();
        // удаление пользователя
        $this->delete();
    }

    public function uploadAvatar($image)
    {
        // если аватар загружен сохранение его в папке
        if($image == null) { return; }

        $this->removeAvatar();

        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->avatar = $filename;
        $this->save();
    }

    public function removeAvatar()
    {   
        // удаление аватара если он был
        if($this->avatar != null)
        {
            Storage::delete('uploads/' . $this->avatar);
        }
    }

    public function getAvatar()
    {
        // вернуть аватар или его замену
        if($this->avatar == null)
        {
            return '/img/no-user-avatar.png';
        }

        return '/uploads/' . $this->avatar;
    }

    public function makeAdmin()
    {
        // сделать пользователя админом
        $this->is_admin = 1;
        $this->save();
    }

    public function makeNormal()
    {
        // разжаловать пользователя
        $this->is_admin = 0;
        $this->save();
    }

    public function toggleAdmin($value)
    {
        // переключатель админ/не админ
        if($value == nul) {return $this->makeNormal();}

        return $this->makeAdmin();
    }

    public function ban()
    {
        // пользователь забанен
        $this->status = 1;
        $this->save();
    }

    public function unban()
    {
        // не в бане
        $this->status = 0;
        $this->save();
    }

       public function toggleBan($value)
    {
        // переключатель бан/не бан
        if($value == nul) {return $this->unban();}

        return $this->ban();
    }



}
