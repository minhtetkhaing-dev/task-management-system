<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimesheetController;

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Task Routes
Route::get('/task', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/task/store', [TaskController::class, 'store'])->name('task.store'); //create
Route::post('/task/delete', [TaskController::class, 'delete'])->name('tasks.delete'); //delete
Route::post('/task/update', [TaskController::class, 'update'])->name('task.update'); //update
Route::post('/task/filter', [TaskController::class, 'filter'])->name('tasks.filter'); //filter

// Project Routes
Route::get('/project', [ProjectController::class, 'index'])->name('projects.index');
Route::post('/project/store', [ProjectController::class, 'store'])->name('project.store'); //create
Route::post('/project/delete', [ProjectController::class, 'delete'])->name('projects.delete'); //delete
Route::post('/project/update', [ProjectController::class, 'update'])->name('project.update'); //update
Route::post('/project/filter', [ProjectController::class, 'filter'])->name('projects.filter'); //filter

// Timesheet Routes
Route::get('/timesheet', [TimesheetController::class, 'index'])->name('timesheets.index');
Route::post('/timesheet/store', [TimesheetController::class, 'store'])->name('timesheet.store'); //create
Route::post('/timesheet/delete', [TimesheetController::class, 'delete'])->name('timesheets.delete'); //delete
Route::post('/timesheet/update', [TimesheetController::class, 'update'])->name('timesheet.update'); //update
Route::post('/timesheet/filter', [TimesheetController::class, 'filter'])->name('timesheets.filter'); //filter