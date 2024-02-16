<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\Api;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;

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


// Combine login and update profile routes
Route::get('/login', [LoginController::class, 'Login']);

Route::get('/updateprofile',[LoginController::class, 'update_profile']);

Route::get('/requestreset',[LoginController::class,'requestReset']);

Route::post('/resetpassword',[LoginController::class,'resetPassword']);



// Define routes for admin
Route::prefix('admin')->group(function () {
    // Routes for adding category
    Route::post('/addcategory', [AdminController::class, "AddCategory"]);

    Route::get('/getallcategories',[AdminController::class,'GetAllCategories']);

    Route::put('/editcategory/{id}',[AdminController::class,'EditCategory']);

    Route::post('/addDua',[AdminController::class,'AddDua']);

    Route::get('/getallduas',[AdminController::class,'GetAllDuas']);

    Route::get('/getduabycategory/{id}',[AdminController::class,'GetDuasByCategory']);

    Route::put('/editdua/{id}',[AdminController::class,'EditDuaByDuaId']);

    Route::delete('/deletedua/{id}',[AdminController::class,'DeleteDua']);

});


// Define routes for user
Route::prefix('user')->group(function () {

    Route::post('/signup',[UserController::class,'signup']);

    Route::get('/getallcategories',[UserController::class,'GetAllCategories']);

    Route::get('/getduabycategory/{id}',[UserController::class,'GetDuaByCategoryId']);

    Route::get('/getduabyduaid/{id}',[UserController::class,'GetDuaByDuaId']);

    Route::Post('/favoritedua/{dua_id}/{user_id}',[UserController::class,'FavoriteDua']);

    Route::delete('/unfavoritedua/{dua_id}/{user_id}',[UserController::class,'UnfavoriteDua']);

    Route::get('/getfavoritebyuserid/{id}',[UserController::class,'GetFavoriteDuabyUserId']);

    Route::post('/storecounter/{dua_id}',[UserController::class,'updateCounter']);

    Route::delete('/resetcounter/{dua_id}/{user_id}',[UserController::class,'ResetCounter']);

    Route::get('/getremainderbyuserid/{id}',[UserController::class,'getRemainder']);

    Route::post('/setremainder',[UserController::class,'setReminder']);

    Route::delete('/delremainder',[UserController::class,'deleteReminder']);


    Route::post('/addDua',[UserController::class,'AddDua']);

    Route::get('/getallduas',[UserController::class,'GetAllDuas']);

    Route::get('/getduabycategory',[UserController::class,'GetDuasByCategory']);

    Route::delete('/deletedua/{id}',[USerController::class,'DeleteDua']);

});


