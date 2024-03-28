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

Route::get('/', [\App\Http\Controllers\Frontend\FrontendController::class, 'index']);
Route::get('/detail/news/{slug}', [\App\Http\Controllers\Frontend\FrontendController::class, 'detailNews'])->name('detailNews');
Route::get('/detail/category/{slug}', [\App\Http\Controllers\Frontend\FrontendController::class, 'detailCategory'])->name('detailCategory');

Auth::routes();

    
// Auth::routes();

// Route::match(['get','post'], '/register', function(){
//     return redirect('/login');   
// });



Route::resource('news', NewsController::class);

//route middleware
Route::middleware('auth')->group(function () {
    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/profile', [\App\Http\Controllers\Profile\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/change-password', [\App\Http\Controllers\Profile\ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/update-password', [\App\Http\Controllers\Profile\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/create-profile', [\App\Http\Controllers\Profile\ProfileController::class, 'createProfile'])->name('createProfile');
    Route::post('/store-profile', [\App\Http\Controllers\Profile\ProfileController::class, 'storeProfile'])->name('storeProfile');
    Route::get('/edit-profile', [\App\Http\Controllers\Profile\ProfileController::class, 'editProfile'])->name('editProfile');
    Route::put('/update-profile', [\App\Http\Controllers\Profile\ProfileController::class, 'updateProfile'])->name('updateProfile');

    // route for admin
    Route::middleware(['auth', 'admin'])->group(function(){
        Route::resource('news', NewsController::class);

        // Fungsi except('show') itu untuk menghilangkan fungsi show karena kita tidak menggunakan show
        // Fungsi only('index') itu untuk menampilkan fungsi index saja karena kita hanya akan menggunakan index
        Route::resource('category', CategoryController::class)->except('show');

        // get all user
        Route::get('/all-user', [\App\Http\Controllers\Profile\ProfileController::class, 'allUser'])->name('allUser');
        // reset password user
        Route::put('/reset-password/{id}', [\App\Http\Controllers\Profile\ProfileController::class, 'resetPassword'])->name('resetPassword');
    });
});