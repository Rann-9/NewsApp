<?php

namespace App\Http\Controllers\Frontend;

use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontendController extends Controller
{
    public function index()
    {
        // get data category
        $category = Category::latest()->get();
        $sliderNews = News::latest()->limit(3)->get();

        return view('frontend.news.index', compact(
            'category',
            'sliderNews'
        ));
    }

    public function detailNews($slug)
    {
        // get data category
        $category = Category::latest()->get();

        // get data news
        $news = News::where('slug', $slug)->first();

        $sideNews = News::latest()->limit(3)->get();

        return view('frontend.news.detail', compact(
            'category',
            'news',
            'sideNews'
        ));
    }

    public function detailCategory($slug)
    {
        // get data category
        $category = Category::latest()->get();

        // get data category by slug
        $detailCategory = Category::where('slug', $slug)->first();

        // get data news by category
        $news = News::where('category_id', $detailCategory->id)->latest()->get();

        $sideNews = News::latest()->limit(3 )->get();

        $allNews = News::latest()->get();

        return view('frontend.news.detail-category', compact(
            'category',
            'detailCategory',
            'news',
            'sideNews',
            'allNews'
        ));
    }
}
