<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function show($username)
    {
        $author = \App\Models\User::where('username', $username)->first();
        return view('pages.author.show', compact('author'));
    }
}
