<?php

namespace App\Http\Controllers\Admin;

use App\Tag;
use App\Category;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        // страница index
        $posts = Post::all();
        return view('admin.posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // загрузга данных из бд
        $tags = Tag::pluck('title', 'id')->all();
        $categories = Category::pluck('title', 'id')->all();
        // страница создания поста
        return view('admin.posts.create', compact('categories', 'tags'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // загрузка в бд
        // валидация загрузки данных
        $this->validate($request, [
            'title' => 'required',  // обязательные поля к заполнению
            'content' => 'required',
            'date' => 'required',
            'image' => 'image|nullable',   // поле image изображение или пустое
        ]);

        $post = Post::add($request->all());
        $post->uploadImage($request->file('image'));
        $post->setCategory($request->get('category_id'));
        $post->setTags($request->get('tags'));
        $post->toggleStatus($request->get('status'));
        $post->toggleFeatured($request->get('is_featured'));

        return redirect()->route('posts.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // изменение поста
        $post = Post::find($id);
        // загрузга категорий и тегов из бд
        $tags = Tag::pluck('title', 'id')->all();
        $categories = Category::pluck('title', 'id')->all();
        // получение тегов редактируемого поста
        $selectedTags = $post->tags->pluck('id')->all();
        return view('admin.posts.edit', compact(
            'post', 
            'categories',
            'selectedTags',
            'tags'));
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
        // приминение изменений поста
                $this->validate($request, [
            'title' => 'required',  // обязательные поля к заполнению
            'content' => 'required',
            'date' => 'required',
            'image' => 'image|nullable',   // поле image изображение или пустое
        ]);
        $post = Post::find($id);
        $post->edit($request->all());
        $post->uploadImage($request->file('image'));
        $post->setCategory($request->get('category_id'));
        $post->setTags($request->get('tags'));
        $post->toggleStatus($request->get('status'));
        $post->toggleFeatured($request->get('is_featured'));

        return redirect()->route('posts.index');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // удаление поста
        $post = Post::find($id)->remove();
        return redirect()->route('posts.index');   
    }
}
