<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\connectionsController;
use App\Http\Controllers\api\downloadController;
use App\Http\Controllers\api\fetchController;
use App\Http\Controllers\api\homeController;
use App\Http\Controllers\api\likeController;
use App\Http\Controllers\api\profileController;
use App\Http\Controllers\api\profileEducationController;
use App\Http\Controllers\api\profileEmployeeController;
use App\Http\Controllers\api\profileFundingController;
use App\Http\Controllers\api\profileProfessionalController;
use App\Http\Controllers\api\profileWorksController;
use App\Http\Controllers\api\savedController;
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

Route::post('/homeFeed', [homeController::class, 'homeFeed']);

Route::post('/uploadJournal', [UploadController::class, 'upload_journals']);

Route::post('/uploadPost', [UploadController::class, 'upload_posts']);

Route::post('/deletePost', [UploadController::class, 'delete_post']);

Route::post('/deleteJournal', [UploadController::class, 'delete_journal']);


Route::post('/userList', [searchController::class, 'listOfUsers']);

// Like API
Route::post('/like', [likeController::class, 'likeCreate']);
Route::post('/unlike', [likeController::class, 'unlikeDelete']);

// Saved API
Route::post('/save', [savedController::class, 'saveCreate']);
Route::post('/deleteSave', [savedController::class, 'deleteSave']);
// Route::post("/listSaved", [savedController::class, 'listOfSaved']);

// Download API
Route::post('/downloadArticle', [downloadController::class, 'downloadArticle']);

// Connections
Route::post("/follow", [connectionsController::class, "followCreate"]);
Route::post("/unfollow", [connectionsController::class, "unfollow"]);
Route::post("/connection", [connectionsController::class, "fetchConnectionsList"]);

Route::post("/fetchArticle", [fetchController::class, "fetchArticle"]);

// profiles
Route::post("/profileAdd", [profileController::class, "create"]);
Route::post("/profileEdit/{id}", [profileController::class, "edit"]);
Route::post("/profileDelete/{id}", [profileController::class, "destroy"]);

// profile Employee
Route::post("/profileEmpAdd", [profileEmployeeController::class, "create"]);
Route::post("/profileEmpEdit/{id}", [profileEmployeeController::class, "edit"]);
Route::post("/profileEmpDelete/{id}", [profileEmployeeController::class, "delete"]);

// profile Education
Route::post("/profileEduAdd", [profileEducationController::class, "create"]);
Route::post("/profileEduEdit/{id}", [profileEducationController::class, "edit"]);
Route::post("/profileEduDelete/{id}", [profileEducationController::class, "delete"]);

// profile professional activities
Route::post("/profileProAdd", [profileProfessionalController::class, "create"]);
Route::post("/profileProEdit/{id}", [profileProfessionalController::class, "edit"]);
Route::post("/profileProDelete/{id}", [profileProfessionalController::class, "delete"]);

// profile funding details
Route::post("/profileFundAdd", [profileFundingController::class, "create"]);
Route::post("/profileFundEdit/{id}", [profileFundingController::class, "edit"]);
Route::post("/profileFundDelete/{id}", [profileFundingController::class, "delete"]);

// profile works
Route::post("/profileWorkAdd", [profileWorksController::class, "create"]);
Route::post("/profileWorkEdit/{id}", [profileWorksController::class, "edit"]);
Route::post("/profileWorkDelete/{id}", [profileWorksController::class, "delete"]);

// fetch profile
Route::post("/fetchProfile", [profileController::class, "fetchProfile"]);
