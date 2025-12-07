<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkOrderLabelController;
use App\Http\Controllers\ProcessLabelController;
use App\Http\Controllers\MachineLabelController;

Route::get('/', function () {
    return redirect('/wellbest/login');
});

Route::get('/operator', function () {
    return view('operator.menu');
})->name('operator.menu');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/work-order/{workOrder}/print-label', [WorkOrderLabelController::class, 'printLabel'])->name('workorder.print-label');
Route::get('/process-master/{process}/print-label', [ProcessLabelController::class, 'printLabel'])->name('process.print-label');
Route::get('/machine-master/{machine}/print-label', [MachineLabelController::class, 'printLabel'])->name('machine.print-label');

Route::get('/process-master/print-multiple-labels', [ProcessLabelController::class, 'printMultipleLabels'])
    ->name('process.print-multiple-labels');

Route::get('/machine-master/print-multiple-labels', [MachineLabelController::class, 'printMultipleLabels'])
    ->name('machine.print-multiple-labels');    

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
