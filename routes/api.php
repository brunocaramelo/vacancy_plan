<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{HolidayPlanController,
                          LoginController,
                          ReportController};



Route::controller(LoginController::class)
->prefix('/login')
->group(function(){
    Route::post('/', 'doLogin')->name('do-login');
    Route::get('/', function (){
        return response()->json(['message' => 'Unauthenticated'], 401);
    })->name('login');
});

Route::controller(HolidayPlanController::class)
->prefix('/holidays')
->middleware('auth:sanctum')
->group(function(){
    Route::get('/', 'listFiltered');
    Route::post('/', 'storeHoliday');
    Route::put('/{id}', 'updateHoliday');
    Route::get('/{id}', 'getVerboseById');
});


Route::controller(ReportController::class)
->prefix('/holiday/report')
->middleware('auth:sanctum')
->group(function(){
    Route::get('/{id}', 'generateReportById');
});

