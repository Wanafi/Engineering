<?php

use App\Http\Controllers\ReportPrintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/reports/{type}/preview', [ReportPrintController::class, 'listPreview'])->name('reports.preview');
    Route::get('/reports/{type}/{id}/print', [ReportPrintController::class, 'single'])->name('reports.single');
});
