<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TaskAssignmentController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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

require __DIR__.'/auth.php';


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');



// Task Routes (Admin only)
    Route::resource('tasks', TaskController::class);
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Task Assignment Routes (Admin only)
    Route::resource('task-assignment', TaskAssignmentController::class);
    Route::post('/task-assignment/{task}', [TaskAssignmentController::class, 'store'])->name('task-assignment.store');
    Route::get('/task-assignment/add/{id}', [TaskAssignmentController::class, 'add'])->name('task-assignment.add');
    Route::get('/task-assignment/{id}', [TaskAssignmentController::class, 'destroy'])->name('task-assignment.destroy');
    
    Route::get('/task-assignment/remove/{id}', [TaskAssignmentController::class, 'getRemoveUser'])->name('task-assignment.remove');
    Route::post('/task-assignment/{taskId}/remove-users', [TaskAssignmentController::class, 'removeUsers'])->name('task-assignment.removeUsers');

});

