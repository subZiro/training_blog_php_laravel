<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    public static function add($email)
    {
    	// добавить подписчика
    	$sub = new static;
    	$sub->email = $email;
    	$sub->token = str_random(100);
    	$sub->save();

    	return $sub;
    }		

    public function remove()
    {
    	// удаление подписчика
    	$this->delete();
    }
}
