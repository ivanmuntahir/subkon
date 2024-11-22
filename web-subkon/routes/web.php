<?php

use App\Livewire\Assignment;
use Filament\Pages\Dashboard;
use App\Filament\Resources\ProjectResource\Pages\ListProjects;
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

Route::get('/projects/{id}/assign-employees', Assignment::class)->name('projects.assignEmployees');

//buat route nembak function
Route::get('assign-employees', [Assignment::class, 'newbie'])->name('ivan');
//sama
Route::post('project', [Assignment::class, 'assignEmployees'])->name('pegawai');

//mengambil boss
Route::get('sandana/projects', [ListProjects::class, 'sendBack'])->name('sandana.projects');

Route::get('/', function () {
    return view('welcome');
});


