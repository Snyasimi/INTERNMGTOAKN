<?php
use App\Http\Controllers\{TaskController,
	LoginController,
	UsersController,
	AccountActivator,
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

Route::get('/',[LoginController::class,'login']);
//Route::resource('login',LoginController::class);
Route::post('login',[LoginController::class,'login'])->name('login');

Route::resource('Apply',ApplicantsController::class);
Route::post('ApplicationStatus',[AccountActivator::class,'CheckStatus']);
Route::post('PasswordReset',[AccountActivator::class,'RequestPasswordReset'])->name('Requestpasswordreset');
Route::post('Reset/{id}',[AccountActivator::class,'ResetPassword'])->name('ResetPassword');
Route::post("Account/Activate",[AccountActivator::class,'Activate'])->name('ActivateAccount');
Route::delete('Account/Deactivate',[AccountActivator::class,'Deactivate'])->name('DeactivateAccount');
Route::resource('Position',PositionController::class);

// PROTECTED ROUTES
Route::middleware(['auth:sanctum'])->group(function(){

	Route::prefix('Admin')->group(function(){

		Route::get('User/Dashboard',[UsersController::class,'index'])->name('AdminDashboard');
		Route::get('User/Supervisors',[UsersController::class,'index'])->name('AdminSupervisors');
		Route::get('User/Applicants',[UsersController::class,'index'])->name('AdminApplicants');
		Route::get('http://192.168.1.214:3000/adminDashboard',[LoginController::class,'login']);

	})->middleware('ability:Admin');

	Route::prefix('Supervisor')->group(function(){

		Route::get('User/AssignedTasks',[UsersController::class,'index'])->name('SupervisorAssignedTasks');
		Route::get('User/MyInterns',[UsersController::class,'index'])->name('SupervisorMyInterns');

	})->middleware('ability:Admin');

	Route::resource('User',Userscontroller::class);
	Route::resource('Task',TaskController::class);
	
    Route::resource('Roles',RolesController::class);
	Route::post('Comment',[CommentController::class,'store']);
	Route::post('logout',[LoginController::class,'logout']);


	}
);

