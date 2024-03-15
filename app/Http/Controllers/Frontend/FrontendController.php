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
        // get data news by Category
        $categoryNews = News::with('category')->latest()->get();

        return view('frontend.news.index', compact(
            'category',
            'categoryNews'
        ));
    }
}
