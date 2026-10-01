<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnualCalendarController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PtmController;



Route::get('/', [AnnualCalendarController::class, 'index'])
    ->name('annual_calendar.index');

Route::get('/annual_calendar/create', [AnnualCalendarController::class, 'create'])
    ->name('annual_calendar.create');

Route::post('/annual_calendar', [AnnualCalendarController::class, 'store'])
    ->name('annual_calendar.store');



Route::get('/holiday', [HolidayController::class, 'index'])
    ->name('holiday.index');

Route::get('/holiday/create', [HolidayController::class, 'create'])
    ->name('holiday.create');

Route::post('/holiday', [HolidayController::class, 'store'])
    ->name('holiday.store');

Route::get('/holiday/{holiday}/edit', [HolidayController::class, 'edit'])
    ->name('holiday.edit');

Route::put('/holiday/{holiday}', [HolidayController::class, 'update'])
    ->name('holiday.update');

Route::delete('/holiday/{holiday}', [HolidayController::class, 'destroy'])
    ->name('holiday.destroy');


Route::get('/event', [EventController::class, 'index'])
    ->name('event.index');

Route::get('/event/create', [EventController::class, 'create'])
    ->name('event.create');

Route::post('/event', [EventController::class, 'store'])
    ->name('event.store');

Route::get('/event/{event}/edit', [EventController::class, 'edit'])
    ->name('event.edit');

Route::put('/event/{event}', [EventController::class, 'update'])
    ->name('event.update');

Route::delete('/event/{event}', [EventController::class, 'destroy'])
    ->name('event.destroy');


Route::get('/ptm', [PtmController::class, 'index'])
    ->name('ptm.index');

Route::get('/ptm/create', [PtmController::class, 'create'])
    ->name('ptm.create');

Route::post('/ptm', [PtmController::class, 'store'])
    ->name('ptm.store');

Route::get('/ptm/{ptm}/edit', [PtmController::class, 'edit'])
    ->name('ptm.edit');

Route::put('/ptm/{ptm}', [PtmController::class, 'update'])
    ->name('ptm.update');

Route::delete('/ptm/{ptm}', [PtmController::class, 'destroy'])
    ->name('ptm.destroy');


Route::get('/holiday/export', [HolidayController::class, 'export'])
    ->name('holiday.export');

Route::get('/holiday/export/excel', [HolidayController::class, 'exportExcel'])
    ->name('holiday.export.excel');

Route::get('/holiday/export/pdf', [HolidayController::class, 'exportPdf'])
    ->name('holiday.export.pdf');

Route::get('/event', [EventController::class, 'index'])
    ->name('event.index');

Route::get('/event/create', [EventController::class, 'create'])
    ->name('event.create');

Route::post('/event', [EventController::class, 'store'])
    ->name('event.store');


/*
|--------------------------------------------------------------------------
| Event Export
|--------------------------------------------------------------------------
*/

Route::get('/event/export', [EventController::class, 'export'])
    ->name('event.export');

Route::get('/event/export/excel', [EventController::class, 'exportExcel'])
    ->name('event.export.excel');

Route::get('/event/export/pdf', [EventController::class, 'exportPdf'])
    ->name('event.export.pdf');


/*
|--------------------------------------------------------------------------
| Event Edit / Update / Delete
|--------------------------------------------------------------------------
*/

Route::get('/event/{event}/edit', [EventController::class, 'edit'])
    ->name('event.edit');

Route::put('/event/{event}', [EventController::class, 'update'])
    ->name('event.update');

Route::delete('/event/{event}', [EventController::class, 'destroy'])
    ->name('event.destroy');

Route::get('/ptm/export', [PtmController::class, 'export'])
    ->name('ptm.export');

Route::get('/ptm/export/excel', [PtmController::class, 'exportExcel'])
    ->name('ptm.export.excel');

Route::get('/ptm/export/pdf', [PtmController::class, 'exportPdf'])
    ->name('ptm.export.pdf');