<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{RolesController,
    PositionController,
    LoginController,
    UsersController,
    TaskController,
    ApplicantsController,
    AccountActivator};
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
    return redirect()->away('http://oaknet.internmanagementsystem.live:9005');
});

