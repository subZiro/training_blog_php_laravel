<?php

namespace App\Http\Controllers\Admin;

use App\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // перенаправление на index
        $tags = Tag::all();
        return view('admin.tags.index', ['tags' => $tags]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // метод открытия страницы создание категории
        return view('admin.tags.create');
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
            'title' => 'required'  // поле title обязательно к заполнению
        ]);
        Tag::create($request->all());
        return redirect()->route('tags.index');  // возврат к тегам
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        // пере направление на страницу редактирования тега
        $tag = Tag::find($id);
        return view('admin.tags.edit', ['tag' => $tag]);

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
        // поле title обязательно к заполнению
        $this->validate($request, [
            'title' => 'required'
        ]);
        // обновление значения тега
        $tag = Tag::find($id);
        $tag->update($request->all());
        return redirect()->route('tags.index');  // перенаправление к тегам
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        // удаление тега 
        $tags = Tag::find($id)->delete();
        return redirect()->route('tags.index');  // возврат к тегам
    }
}
