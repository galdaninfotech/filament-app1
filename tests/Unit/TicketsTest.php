<?php

test('table generation', function () {
    $table = new \App\Classes\Table;
    $table->generate();
    $tickets = $table->getTickets();
 
    //rule #1
    expect($tickets)->toHaveCount(6, "Table must have exactly 6 tickets.");
 
    $tableNumbers = [];
 
    for($i=0; $i<9; $i++)
        $tableCols[$i] = [];
 
    foreach($tickets as $ticket)
    {
        //rule #6, #7 and #8
        // dump($ticket);
        expect($ticket->rowCount(0))->toEqual(5, "First Rows must have exactly 5 numbers.");
        expect($ticket->rowCount(1))->toEqual(5, "Second Rows must have exactly 5 numbers.");
        expect($ticket->rowCount(2))->toEqual(5, "Third Rows must have exactly 5 numbers.");
 
        $nums = $ticket->getNumbers();
        foreach($nums as $num)
            $tableNumbers = array_merge($tableNumbers, $num);
 
        for($i=0; $i<9; $i++)
            $tableCols[$i] = array_merge($tableCols[$i], $ticket->getBucket($i));
    }
 
    //remove 0s
    foreach($tableNumbers as $k => $num)
        if($num == 0)
            unset($tableNumbers[$k]);

    // dd(count($tableNumbers));
    // dd($tableNumbers[$num]['value']);
    // dd(array_diff($tableNumbers['value'], range(1, 90)));

    //rule #2
    expect($tableNumbers)->toHaveCount(90, "Ticket must have exactly 90 numbers.");
    expect(count($tableNumbers))->toBe(90);
    // expect(empty(array_diff($tableNumbers['value'], range(1,90))))->toBeTrue("Table must have all numbers from 1 to 90");
 
    //rule #3, #4 and #5
    expect(empty(array_diff($tableCols[0], range(1,9))))->toBeTrue("1st column must have numbers from 1 to 9");
    expect(empty(array_diff($tableCols[1], range(10,19))))->toBeTrue("2nd column must have numbers from 10 to 19");
    expect(empty(array_diff($tableCols[2], range(20,29))))->toBeTrue("3rd column must have numbers from 20 to 29");
    expect(empty(array_diff($tableCols[3], range(30,39))))->toBeTrue("4th column must have numbers from 30 to 39");
    expect(empty(array_diff($tableCols[4], range(40,49))))->toBeTrue("5th column must have numbers from 40 to 49");
    expect(empty(array_diff($tableCols[5], range(50,59))))->toBeTrue("6th column must have numbers from 50 to 59");
    expect(empty(array_diff($tableCols[6], range(60,69))))->toBeTrue("7th column must have numbers from 60 to 69");
    expect(empty(array_diff($tableCols[7], range(70,79))))->toBeTrue("8th column must have numbers from 70 to 79");
    expect(empty(array_diff($tableCols[8], range(80,90))))->toBeTrue("9th column must have numbers from 80 to 90");
});