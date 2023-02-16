<?php
use App\Http\Controllers\{TaskController,
	LoginController,
	UsersController,
	DepartmentController,
	ApplicantsController,
	CommentController,
	RolesController,
	PositionController	
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

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
//Route::resource('login',LoginController::class);
Route::post('login',[LoginController::class,'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function(){
	Route::post('logout',[LoginController::class,'logout']);
	Route::resource('Task',TaskController::class);
	Route::resource('User',Userscontroller::class)->middleware(['ability:doanything,assigneoles']);

	Route::resource('Department',DepartmentController::class);
	Route::resource('Apply',ApplicantsController::class);
	Route::post('Comment',[CommentController::class,'store']);
	Route::resource('Roles',RolesController::class);
	Route::resource('Position',PositionController::class);
	}
);
