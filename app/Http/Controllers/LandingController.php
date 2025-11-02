<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Author;
use App\Models\Banner;
use Illuminate\Http\Request;


class LandingController extends Controller
{
    public function index() {
        $banners = Banner::with(['news.author.user', 'news.newsCategory'])->get();
        $featureds = News::where('is_featured', true)
            ->with(['author.user', 'newsCategory'])
            ->get();
        $news = News::with(['author.user', 'newsCategory'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        $authors = Author::with('user')
            ->take(3)
            ->get();
        return view('pages.landing', compact('banners','featureds','news', 'authors'));
    }
}