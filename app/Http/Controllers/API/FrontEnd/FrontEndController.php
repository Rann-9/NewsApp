<?php

namespace App\Http\Controllers\API\FrontEnd;

use App\Models\News;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class FrontEndController extends Controller
{
    public function index()
    {
        try {
            // get carousel from news
            $news = News::latest()->limit(3)->get();
            return ResponseFormatter::success(
                $news,
                'Data list of carousel'
            );
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }
}
