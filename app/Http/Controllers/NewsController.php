<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function show($slug) 
    {
        $news = News::where('slug', $slug)->first();
        if (!$news) {
            abort(404);
        }
        $newests = News::orderBy('created_at', 'desc')->take(4)->get();
        return view('pages.news.show', compact('news','newests'));
    }

    public function category($slug) 
    {
        $category = NewsCategory::where ('slug', $slug)->first();
        return view('pages.news.category', compact('category'));
    }
}
