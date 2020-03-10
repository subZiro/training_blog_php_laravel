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
        'name', 'email', 'password', 'status',
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

    public function generatePassword($password, $oldpassword)
    {
        // метод для шифрования нового пароля или сохранение старого
        if(is_null($password))
        {
            $this->password = $oldpassword;
        } else {
            $this->password = bcrypt($password);
        }
        $this->save();
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
        if(is_null($image)) { return; }

        $this->removeAvatar();

        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->avatar = $filename;
        $this->save();
    }

    public function removeAvatar()
    {   
        // удаление аватара если он был
        if(!is_null($this->avatar))
        {
            Storage::delete('uploads/' . $this->avatar);
        }
    }

    public function getAvatar()
    {
        // вернуть аватар или его замену
        if(is_null($this->avatar))
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
        if(is_null($value)) {return $this->makeNormal();}

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
        if(is_null($value)) {return $this->unban();}
        return $this->ban();
    }

    public function getStatus()
    {
        // получение статуса
        if(is_null($this->status)){
            return 'Нет статуса';
        }
        return $this->status;
    }

}
