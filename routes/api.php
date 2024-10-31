<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\searchController;
use App\Http\Controllers\Api\UploadController;

use function Laravel\Prompts\search;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/test', function () {
    die('Test route works!');
});



Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/homeFeed', [AuthController::class, 'homeFeed']);

Route::post('/uploadJournal', [UploadController::class, 'upload_journals']);

Route::post('/uploadPost', [UploadController::class, 'upload_posts']);

Route::post('/deletePost', [UploadController::class, 'delete_post']);

Route::post('/deleteJournal', [UploadController::class, 'delete_journal']);


Route::post('/userList', [searchController::class, 'listOfUsers']);