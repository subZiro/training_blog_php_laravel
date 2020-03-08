<?php

namespace App;

use App\Comment;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function post()
    {
    	return $this->beLongsTo(Post::class);
    }

    public function author()
    {
    	return $this->beLongsTo(User::class, 'user_id');
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

    public function remove()
    {
    	// удаление комментария
    	$this->delete();
    }

}
