<?php

use Illuminate\Http\Request;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;



Route::apiResource('tasks', TaskController::class);

// Or explicit if you prefer:
Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);
