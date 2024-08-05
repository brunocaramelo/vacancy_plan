<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HolidayPlanController;


Route::controller(HolidayPlanController::class)
->prefix('/holidays')
->group(function(){
    Route::get('/', 'listFiltered');
    Route::post('/', 'storeHoliday');
    Route::put('/{id}', 'updateHoliday');
    Route::get('/{codeStrategy}/{hour}/{instant}/prioridade', 'findByIdentityAndHourInstant');
});

