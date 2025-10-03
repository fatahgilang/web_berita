<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Author;
use App\Models\Banner;
use Illuminate\Http\Request;


class LandingController extends Controller
{
    public function index() {
        $banners= Banner::all();
        $featureds = News::where('is_featured', true)
        ->get();
        $news = News::orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        $authors = Author::all()->take(3);
        return view('pages.landing', compact('banners','featureds','news', 'authors'));
    }
}
