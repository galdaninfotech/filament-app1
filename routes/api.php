<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Ticket;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('tickets', function() {
    return Ticket::all();
});

Route::middleware('auth:sanctum')->post('/tickets', function(Request $request) {
    dd($request);
    return Ticket::create($request->all);
});
