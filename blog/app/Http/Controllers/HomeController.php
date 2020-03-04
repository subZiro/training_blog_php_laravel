<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    	// главная страница блога
        $posts = Post::paginate(3);
        return view('pages.index', ['posts'=>$posts]);
    }

    public function show($slug)
    {
        // главная страница поста
        $post = Post::where('slug', $slug)->firstOrFail();
        return view('pages.show', compact('post'));
    }
}
