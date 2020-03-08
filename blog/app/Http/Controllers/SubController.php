<?php

namespace App\Http\Controllers;

use App\Subscribe;
use App\Mail\SubEmail;
use Illuminate\Http\Request;

class SubController extends Controller
{
    public function subscribe(Request $request)
    {
    	$this->validate($request, [
    		'email'	=>	'required|email|unique:subscribes'
    	]);

    	$sub = Subscribe::add($request->get('email'));
    	\Mail::to($sub)->send(new SubEmail($sub));

    	return redirect()->back()->with('status','Проверьте вашу почту!');
    }

    public function verificate($token)
    {
    	$sub = Subscribe::where('token', $token)->firstOrFail();
    	$sub->token = null;
    	$sub->save();
    	return redirect('/')->with('status', 'Ваша почта подтверждена!');
    }
}
