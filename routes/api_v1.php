<?php

use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\AuthorTicketController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tickets', TicketController::class)->except(['update']);
    Route::put('tickets/{ticket}', [TicketController::class, 'replace'])->name('tickets.replace');
    Route::patch('tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');

    Route::apiResource('users', UserController::class)->except('update');
    Route::put('users/{user}', [UserController::class, 'replace'])->name('users.replace');
    Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');


    Route::apiResource('authors', AuthorController::class)->except(['store', 'update', 'delete']);
    Route::apiResource('authors.tickets', AuthorTicketController::class)->except(['update']);
    Route::put('authors/{author}/tickets/{ticket}', [AuthorTicketController::class, 'replace'])->name('authors.tickets.replace');
    Route::patch('authors/{author}/tickets/{ticket}', [AuthorTicketController::class, 'update'])->name('authors.tickets.update');
});
