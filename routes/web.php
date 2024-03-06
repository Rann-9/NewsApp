<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

    
// Auth::routes();

// Route::match(['get','post'], '/register', function(){
//     return redirect('/login');   
// });



Route::resource('news', NewsController::class);

//route middleware
Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // route for admin
    Route::middleware(['auth', 'admin'])->group(function(){
        Route::resource('category', CategoryController::class)->middleware('auth');
    });
});