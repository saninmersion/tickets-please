<?php

use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\AuthorTicketController;
use App\Http\Controllers\Api\V1\TicketController;
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

Route::middleware('auth:sanctum')->apiResource('tickets', TicketController::class);
Route::middleware('auth:sanctum')->apiResource('authors', AuthorController::class);
Route::middleware('auth:sanctum')->apiResource('authors.tickets', AuthorTicketController::class);
