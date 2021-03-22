<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

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

Route::get('/test',[Controller::class, 'test']);

Route::get('/', [Controller::class, 'ui']);

Route::get('/get/data', [Controller::class, 'getData'])->name('get_data');

Route::get('/send/data', [Controller::class, 'sendData'])->name('send_data');
