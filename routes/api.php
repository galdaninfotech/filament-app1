<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Ticket;
use App\Models\TicketRepository;
use Illuminate\Support\Str;
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

// Test Routes
Route::post('/receiveTickets', function(Request $request) {
    $newTickets = $request->tickets;
    // return count(array_filter($newTickets[0][0]));
    // return $newTickets;
    // return count($newTickets);
    // Check if the count of numbers is 90
    // if(count($newTickets[0]) !== 90) {
    //     return response()->json(['error' => 'Invalid ticket set. The count of numbers must be 90.'], 400);
    // }

    // // Check if no numbers are same/repeated
    // $flattenedTickets = collect($newTickets)->flatten();
    // if($flattenedTickets->count() !== $flattenedTickets->unique()->count()) {
    //     return response()->json(['error' => 'Invalid ticket set. Numbers must not be repeated.'], 400);
    // }
    foreach($newTickets as $ticket) {
        $generatedObject = []; // Initialize as an empty array for each ticket

        foreach($ticket as $rowIndex => $row) { // Assuming $ticket is an array of rows
            $rowObject = []; // Initialize an empty array for each row
            foreach($row as $cellIndex => $cell) { // Assuming $row is an array of cells
                // Construct each cell with the desired structure
                // return $rowIndex;
                $cellId = $rowIndex.''.$cellIndex;
                $formattedCell = [
                    'id' => $cellId, // Use the 'id' from the cell
                    'value' => $cell, // Use the 'value' from the cell
                    'checked' => 0, // Use the 'checked' status from the cell
                ];
                $rowObject[] = $formattedCell; // Add the formatted cell to the current row
            }
            $generatedObject[] = $rowObject; // Add the current row to the generated object for the ticket
        }

        // Now, $generatedObject is an array of rows, where each row is an array of formatted cells
        // Save this structure as a JSON string in the database
        TicketRepository::create([
            'id' => Str::uuid(),
            'object' => $generatedObject, // Convert the array structure to a JSON string
        ]);
    }
    $tickets = TicketRepository::all();
    return $tickets;
});

