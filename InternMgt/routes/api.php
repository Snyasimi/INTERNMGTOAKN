<?php
use App\Http\Controllers\{TaskController,LoginController,UsersController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/',[UsersController::class,'index']);
Route::resource('login',LoginController::class);
Route::get('login',[LoginController::class,'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function(){
	Route::post('logout',[LoginController::class,'logout']);
	Route::resource('Task',TaskController::class);
	Route::resource('user',Userscontroller::class);
	}
);
