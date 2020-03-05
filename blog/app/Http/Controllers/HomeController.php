<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Post;
use App\Category;
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
        $posts = Post::paginate(5);
      

        return view('pages.index')->with('posts', $posts);
    }

    public function show($slug)
    {
        // главная страница поста
        $post = Post::where('slug', $slug)->firstOrFail();
        return view('pages.show', compact('post'));
    }

    public function tag($slug)
    {
        // выборка постов по тегу
        $tag = Tag::where('slug', $slug)->firstOrFail();
        // вывод постов со статусом=1, количество постов на странице 3
        //$posts = $tag->posts()->where('status', 1)->paginate(4);
        $posts = $tag->posts()->paginate(4);

        return view('pages.list', ['posts'=>$posts]);
    }

    public function category($slug)
    {
        // выборка постов по категории
        $category = Category::where('slug', $slug)->firstOrFail();
        $posts = $category->posts()->paginate(4);

        return view('pages.list', ['posts'=>$posts]);
    }
}
