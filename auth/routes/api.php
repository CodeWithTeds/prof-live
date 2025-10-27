<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\SubtaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::apiResource('projects', ProjectController::class)->parameters(['projects' => 'id']);
Route::apiResource('tasks', TaskController::class);

Route::controller(ProjectController::class)->group(function () {
    Route::get('/projects/status/{status}', 'getByStatus');
    Route::get('/projects/priority/{priority}', 'getByPriority');
    Route::get('/projects/active', 'getActiveProject');
    Route::get('/projects/completed', 'getCompletedProject');
    Route::get('/projects/overdue', 'getOverDueProjects');


    Route::patch('/projects/{id}/status',  'updateStatus');
    Route::patch('project/{id}/priority',  'updatePriority');
});

Route::controller(TaskController::class)->group(function (){
    Route::get('/tasks/status/{status}', 'getByStatus');
    Route::get('/tasks/priority/{priority}', 'getByPriority');
    Route::get('/tasks/by-user', 'getByUser');
    Route::get('/tasks/due-today', 'getDueToday');
    Route::get('/tasks/upcoming', 'getUpcoming');
    Route::get('/tasks/overdue', 'getOverdue');

    // Dependencies
    Route::post('tasks/dependencies', 'addDependency');
    Route::delete('tasks/dependencies', 'removeDependency');
    Route::get('/tasks/{taskId}/dependencies', 'listDependencies');
    Route::get('/tasks/{taskId}/dependents', 'listdependents');

    Route::patch('/tasks/{task}/status', 'updateStatus');
    Route::patch('/tasks/{task}/priorty', 'updatePriority');
});


// Customize endpoint
Route::controller(SubtaskController::class)->group( function (){
    Route::post('tasks/subtasks', 'addSubtask');
    Route::get('task/{taskId}/subtasks', 'listSubtasks');
    Route::delete('tasks/subtasks', 'removeSubtasks');
    Route::get('/tasks/{taskId}/parent', 'getParent');
});


// GET    /tasks/{task}/subtasks  → index()
// POST   /tasks/{task}/subtasks  → store()
// DELETE /tasks/{task}/subtasks/{subtask} → destroy()
// If you want custom endpoints like getParent or addSubtask, you’ll need extra Route::get() definitions.

Route::apiResource('tasks.subtasks', SubtaskController::class)->only(['index', 'store', 'destroy']);

