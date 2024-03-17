<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EditionController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Rutas de autenticación
Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);

//Mediador para proteger las rutas
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/course', CourseController::class);
    Route::apiResource('/employee', EmployeeController::class);
    Route::apiResource('/edition', EditionController::class);
    Route::get('/isprofessor/{employee}', [EmployeeController::class, 'isProfessor']);
    Route::get('/professor', [EmployeeController::class, 'get_qualified_employee']);
    Route::get('/employeeall/{employee}', [EmployeeController::class, 'employee_get_all']);
    
    //Logout
    Route::get('auth/logout', [AuthController::class, 'logout']);
});
