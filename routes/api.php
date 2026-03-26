<?php

use App\Models\UserApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Models\Todo;
use App\Models\Album;
use App\Models\Photo;
use App\Models\Post;
use App\Models\Comment;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



// PUBLIC route (no auth needed)
Route::post('/login', [LoginController::class, 'login']);

// // PROTECTED route (requires token)
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {

    // Users
    Route::get('/users', function () {
        return UserApi::with(['address.geo', 'company'])->get();
    });

    // Todos
    Route::get('/todos', function () {
        return Todo::with('user')->get();
    });


    //Albums
    Route::get('/albums', function () {
        return Album::with('user')->get();
    });

    //Photos
    Route::get('/photos', function () {
        return Photo::with('album')->paginate(50);
    });

    //Posts
    Route::get('/posts', function () {
        return Post::with(['user', 'comments'])->get();
    });

    //Comments
    Route::get('/comments', function () {
        return Comment::with('post')->get();
    });

});

