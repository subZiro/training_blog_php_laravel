<?php

namespace App\Http\Controllers\Admin;

use App\Subscribe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscribersController extends Controller
{
    public function index()
    {
        // страница подписчиков
        $subs = Subscribe::all();
        return view('admin.subs.index', ['subs' => $subs]);
    }

    public function destroy($id)
    {   
        // удаление подписки
        $tags = Subscribe::find($id)->delete();
        return redirect()->route('subs.index');
    }

    
}
