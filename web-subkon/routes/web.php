<?php

use App\Livewire\Assignment;
use Filament\Pages\Dashboard;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\RouteGroup;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::group(['middleware' => 'auth'], function () {
    Route::get('assignment', Assignment::class)->name('assignment');
});

Route::get('/login', function () { 
    return redirect('sandana/login'); 
})->name('login');

Route::get('/projects', function () { 
    return redirect('sandana/projects'); 
})->name('projects');


Route::get('/', function () {
    return view('welcome');
});


