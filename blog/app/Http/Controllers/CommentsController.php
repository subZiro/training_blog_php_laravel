<?php

namespace App\Http\Controllers;

use Auth;
use App\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    //
    public function store(Request $request)	
    {
    	// сохранение комментария
    	$this->validate($request, [
    		'message' => 'required'
    	]);
    	
    	$comment = new Comment;
    	$comment->text = $request->get('message');
    	$comment->user_id = Auth::user()->id;
    	$comment->post_id = $request->get('post_id');

    	$comment->save();
    	
    	return redirect()->back()->with('status', 'Ваш комментарий скоро будет добавлен');
    }
}
