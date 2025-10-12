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

    public function all(Request $request)
    {
        $query = News::with(['author', 'newsCategory'])->orderBy('created_at', 'desc');
        
        // Add search functionality
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }
        
        // Add category filter
        if ($request->has('category') && $request->category) {
            $query->whereHas('newsCategory', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        $news = $query->paginate(12);
        $categories = NewsCategory::all();
        
        return view('pages.news.all', compact('news', 'categories'));
    }

    public function category($slug) 
    {
        $category = NewsCategory::where ('slug', $slug)->first();
        return view('pages.news.category', compact('category'));
    }
}
