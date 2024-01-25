<?php

use Illuminate\Support\Facades\Route;
use App\Classes\Table;
use App\Classes\Test;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {

    $table = new Table();
    $table->generate();

    $tickets = $table->getTickets();
    var_dump(Test::verify($tickets));
    // $table = new Table();
    // $table->generate();
    // // dd($table);
    // dd($table->prettyPrint());
    // dd($table->getTickets());
    // return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
