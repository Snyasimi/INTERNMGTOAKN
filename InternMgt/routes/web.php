<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{UsersController,TaskController,ApplicantsController,DepartmentController};
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

Route::resource('/Apply',ApplicantsController::class);
Route::resource('Dept',DepartmentController::class);
Route::resource('User',UsersController::class);
Route::resource('Task',TaskController::class);
