<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function post()
    {
    	return $this->hasOne(Post::class);
    }

    public function author()
    {
    	return $this->hasOne(User::class);
    }

    public function allow()
    {
    	// одобрить комментарий
    	$this->status = 1;
    	$this->save();
    }

    public function dissAllow()
    {
    	// запретить комментарий
    	$this->status = 0;
    	$this->save();
    }

    public function toggleStatus()
    {
    	// переключатель одобрения комментария
    	if($this->status == 0)
    	{
    		return $this->allow();
    	}
    	return $this->dissAllow();
    }

    public function remive()
    {
    	// удаление комментария
    	$this->delete();
    }

}
