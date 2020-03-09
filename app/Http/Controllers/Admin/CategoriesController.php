<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function index()
    {   
        // открытие страницы индекс в категориях
    	$categories = Category::all();
    	return view('admin.categories.index', ['categories' => $categories]);
    }

    public function create()
    {
        // метод открытия страницы создание категории
    	return view('admin.categories.create');
    }

    public function store(Request $request)
    {	
        //метод создания категории (добавление в бд)
    	$this->validate($request, [
    		'title' => 'required'  // поле title обязательно к заполнению
    	]);
    	Category::create($request->all());
    	return redirect()->route('categories.index');  // возврат к категориям
    }

    public function edit($id)
    {
        // изменение категории
        $category = Category::find($id);
        return view('admin.categories.edit', ['category'=>$category]);

    }

    public function update(Request $request, $id)
    {
        // поле title обязательно к заполнению
        $this->validate($request, [
            'title' => 'required' 
        ]);
        // изменение категории 
        $category = Category::find($id);
        $category->update($request->all());
        return redirect()->route('categories.index');  // перенаправление к категориям
    }

    public function destroy($id)
    {
        // удаление категории
        $category = Category::find($id)->delete();
        return redirect()->route('categories.index');  // возврат к категориям

    }

}
