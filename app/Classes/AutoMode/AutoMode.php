<?php
namespace App\Classes\AutoMode;

// use App\Models\Number;
// use App\Models\Game;
// use App\Models\GameNumber;
use App\Models\Ticket;
// use App\Models\Winner;
use App\Models\User;
use App\Models\Claim;
use App\Events\ClaimEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AutoMode {
    public $newNumber;
    public $drawnNumbers = [];
    public $activeGame;
    public $gamePrizes;
    public $autoTickUsers;
    public $autoClaimUsers;
    public $user;

    function __construct($newNumber) {
        $this->newNumber = $newNumber;
        $this->drawnNumbers = DB::table('game_number')->pluck('number_id')->toArray();
        $this->activeGame = DB::table('games')->where('active', true)->first();
        $this->gamePrizes = DB::table('game_prize')->pluck('name')->toArray();
        $this->autoTickUsers = User::where('autotick', 1)->get();
        $this->autoClaimUsers = User::where('autoclaim', 1)->get();
        $this->user = Auth::user();

    }


    public function updateAutoTickTickets()
    {
        foreach ($this->autoTickUsers as $user) {
            $tickets = Ticket::where('game_id', $this->activeGame->id)
                ->where('user_id', $user->id)
                ->get();

            foreach ($tickets as $ticket) {
                $this->unTickAllNumbers($ticket); // untick number all old numbers
                $this->tickAllNumbers($ticket); // Update all old numbers
                $ticketObject = $ticket->object; // No need to decode if it's already an array
                $modified = false; // Flag to track if any modifications were made to the ticket

                for ($j = 0; $j < 3; $j++) {
                    for ($k = 0; $k < 9; $k++) {
                        // Check if the ticket object is an array and matches the new number
                        if (is_array($ticketObject[$j][$k]) && $ticketObject[$j][$k]['value'] == $this->newNumber) {
                            // Update the 'checked' attribute of the ticket object
                            $ticketObject[$j][$k]['checked'] = 1;
                            $modified = true; // Set the flag to true since modifications were made
                        }
                    }
                }

                // If modifications were made, save the updated ticket
                if ($modified) {
                    $ticket->object = $ticketObject; // No need to encode if it's already an array
                    $ticket->save();
                    // $this->mount();
                }
            }
        }
    }

    public function unTickAllNumbers($ticket) {
        // Tick all numbers the ticket object
        $ticketObject = $ticket->object;
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 9; $j++) {
                if($ticketObject[$i][$j]['value'] > 0) {
                    if(in_array($ticketObject[$i][$j]['value'], $this->drawnNumbers)) {
                        $ticketObject[$i][$j]['checked'] = 0;
                    }
                }
            }
        }
        $ticket->object = $ticketObject;
        $ticket->save();
    }

    public function tickAllNumbers($ticket) {
        // Tick all numbers the ticket object
        $ticketObject = $ticket->object;
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 9; $j++) {
                if($ticketObject[$i][$j]['value'] > 0) {
                    if(in_array($ticketObject[$i][$j]['value'], $this->drawnNumbers)) {
                        $ticketObject[$i][$j]['checked'] = 1;
                    }
                }
            }
        }
        $ticket->object = $ticketObject;
        $ticket->save();
    }

    public function updateAutoClaimTickets() {
        foreach ($this->autoClaimUsers as $user) {
            $tickets = Ticket::where('game_id', $this->activeGame->id)->where('user_id', $user->id)->get();
            foreach ($tickets as $ticket) {
                foreach($this->gamePrizes as $prize) {
                    $functionName = 'check' . str_replace(' ', '', $prize);
                    call_user_func([$this, $functionName], $ticket, $user);
                }
            }
        }
    }

    public function checkFullHouse($ticket, $user) {
        // dd('1-checkTopline');
        $flag = true;
        foreach ($ticket->object as $row) {
            foreach ($row as $number) {
                if($number['value'] > 0){
                    if (!in_array($number['value'], $this->drawnNumbers)) {
                        $flag = false;
                        break;
                    }
                }

            }

        }
        if($flag) {
            $this->autoClaim($ticket->id, $user, $gamePrizeId = 1, $prizeName = 'Full House');
        }
    }

    public function checkQuickFive($ticket, $user) {
        // dd('2-checkTopline');
        $count = 0;
        foreach ($ticket->object as $row) {
            foreach ($row as $number) {
                if($number['value'] > 0){
                    if ($number['checked']) {
                        $count++;
                    }
                }

            }

        }
        if($count > 4) {
            $this->autoClaim($ticket->id, $user, $gamePrizeId = 2, $prizeName = 'Quick Five');
        }
    }

    public function checkLuckySeven($ticket, $user) {
        $count = 0;
        foreach ($ticket->object as $row) {
            foreach ($row as $number) {
                if($number['value'] > 0){
                    if ($number['checked']) {
                        $count++;
                    }
                }

            }

        }
        if($count > 6) {
            $this->autoClaim($ticket->id, $user, $gamePrizeId = 3, $prizeName = 'Quick Five');
        }
    }

    public function checkTopline($ticket, $user) {
        // dd('4-checkTopline');
        $flag = true;
        foreach ($ticket->object[0] as $number) {
            if($number['value'] > 0){
                if (!in_array($number['value'], $this->drawnNumbers)) {
                    $flag = false;
                    break;
                }
            }

        }
        if($flag) {
            $this->autoClaim($ticket->id, $user, $gamePrizeId = 4, $prizeName = 'Top Line');
        }
    }

    public function checkMiddleline($ticket, $user) {
        $flag = true;
        foreach ($ticket->object[1] as $number) {
            if($number['value'] > 0){
                if (!in_array($number['value'], $this->drawnNumbers)) {
                    $flag = false;
                    break;
                }
            }

        }
        if($flag) {
            $this->autoClaim($ticket->id, $user, $gamePrizeId = 5, $prizeName = 'Middle Line');
        }
    }

    public function checkBottomline($ticket, $user) {
        $flag = true;
        foreach ($ticket->object[2] as $number) {
            if($number['value'] > 0){
                if (!in_array($number['value'], $this->drawnNumbers)) {
                    $flag = false;
                    break;
                }
            }

        }
        if($flag) {
            $this->autoClaim($ticket->id, $user, $gamePrizeId = 6, $prizeName = 'Bottom Line');
        }
    }

    public function checkTicketCorner($ticket, $user) {
        $flag = true;

        // Check the first row
        if (isset($ticket->object[0])) {
            $row = $ticket->object[0];
            $firstNumber = null;
            $lastNumber = null;

            foreach ($row as $number) {
                if ($number['value'] > 0) {
                    if ($firstNumber === null) {
                        $firstNumber = $number['value'];
                    }
                    $lastNumber = $number['value'];
                }
            }

            if ($firstNumber !== null && $lastNumber !== null) {
                if (!in_array($firstNumber, $this->drawnNumbers) || !in_array($lastNumber, $this->drawnNumbers)) {
                    $flag = false;
                }
            } else {
                $flag = false;
            }
        } else {
            $flag = false; // Handle case where $ticket->object does not have a first row
        }


        // Check the third row
        if (isset($ticket->object[2])) {
            $row = $ticket->object[2];
            $firstNumber = null;
            $lastNumber = null;

            foreach ($row as $number) {
                if ($number['value'] > 0) {
                    if ($firstNumber === null) {
                        $firstNumber = $number['value'];
                    }
                    $lastNumber = $number['value'];
                }
            }

            if ($firstNumber !== null && $lastNumber !== null) {
                if (!in_array($firstNumber, $this->drawnNumbers) || !in_array($lastNumber, $this->drawnNumbers)) {
                    $flag = false;
                }
            } else {
                $flag = false;
            }
        } else {
            $flag = false; // Handle case where $ticket->object does not have a first row
        }

        if ($flag) {
            $this->autoClaim($ticket->id, $user, $gamePrizeId = 7, $prizeName = 'Ticket Corner');
        }
    }

    public function checkKingsCorner($ticket, $user) {
        $firstRowFirstNumber = null;
        $middleRowFirstNumber = null;
        $bottomRowFirstNumber = null;

        foreach ($ticket->object as $row) {
            foreach ($row as $number) {
                if ($number['value'] > 0) {
                    $firstNumber = $number['value'];
                    if ($number['checked']) {
                        if ($firstRowFirstNumber === null) {
                            $firstRowFirstNumber = $firstNumber;
                        } elseif ($middleRowFirstNumber === null) {
                            $middleRowFirstNumber = $firstNumber;
                        } elseif ($bottomRowFirstNumber === null) {
                            $bottomRowFirstNumber = $firstNumber;
                        }
                        break; // Breaks the inner loop once the first non-zero number in the row is found
                    }
                }
            }
        }

        if ($firstRowFirstNumber !== null && $middleRowFirstNumber !== null && $bottomRowFirstNumber !== null) {
            $this->autoClaim($ticket->id, $user, $gamePrizeId = 8, $prizeName = 'Kings Corner');
        }
    }

    public function checkQueensCorner($ticket, $user) {
        $firstRowLastNumber = null;
        $middleRowLastNumber = null;
        $bottomRowLastNumber = null;

        foreach ($ticket->object as $row) {
            // Check the fifth non-zero number in each row
            $nonZeroCount = 0;
            foreach ($row as $number) {
                if ($number['value'] > 0) {
                    $nonZeroCount++;
                    if ($nonZeroCount === 5) {
                        if ($number['checked']) {
                            if ($firstRowLastNumber === null) {
                                $firstRowLastNumber = $number['value'];
                            } elseif ($middleRowLastNumber === null) {
                                $middleRowLastNumber = $number['value'];
                            } elseif ($bottomRowLastNumber === null) {
                                $bottomRowLastNumber = $number['value'];
                            }
                        }
                        break;
                    }
                }
            }
        }

        if ($firstRowLastNumber !== null && $middleRowLastNumber !== null && $bottomRowLastNumber !== null) {
            $this->autoClaim($ticket->id, $user, $gamePrizeId = 9, $prizeName = 'Queens Corner');
        }
    }

    public function autoClaim($ticketId, $user, $gamePrizeId, $prizeName) {
        // Do not claim if already claimed for the same prize
        $oldClaim = Claim::where('ticket_id', $ticketId)->where('game_prize_id', $gamePrizeId)->where('status', 'Open')->first();
        if($oldClaim) {
            return Session::put('status', 'Already claimed using ticket number : '. $ticketId . ' for prize : ' . $prizeName);
        }

        $claim = Claim::create([
            'ticket_id'     => $ticketId,
            'game_prize_id' => $gamePrizeId,
            'status'        => 'Open',
            'remarks'       => $prizeName.' claimed AUTOMATICALLY for '.$user->name.' for TicketId '.$ticketId
        ]);

        //set active game status to 'Paused'
        DB::table('games')->where('active', true)->update(['status' => 'Paused']);

        //get the new claim and fire event
        $newClaim = DB::table('claims')
            ->join('game_prize', 'claims.game_prize_id', '=', 'game_prize.prize_id')
            ->join('tickets', 'claims.ticket_id', '=', 'tickets.id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->join('games', 'tickets.game_id', '=', 'games.id')
            ->join('prizes', 'game_prize.prize_id', '=', 'prizes.id')
            ->where('claims.id', '=', $claim->id)
            ->where('games.id', '=', $this->activeGame->id)
            ->select(
                    'claims.id as claim_id',
                    'claims.ticket_id',
                    'claims.game_prize_id',
                    'claims.status',
                    'claims.remarks',
                    'claims.created_at',
                    'game_prize.*',
                    'tickets.object as ticket',
                    'tickets.id as ticket_id',
                    'users.name as user_name',
                    'prizes.name as prize_name',
            )
            ->get();

        Session::flash('message', 'Success! Claim sent automatically. Game is paused for processing..');
        // event(new ClaimEvent($newClaim));

        // get quantity of prize and decrement it
        // $prizes = DB::table('game_prize')
        //     ->where('game_id', '=', $this->activeGame->id)
        //     ->where('prize_id', '=', $this->prizeSelected)
        //     ->get();
        // $quantity = $prizes[0]->quantity;
        // if($quantity > 0){
        //     $prizes = DB::table('game_prize')
        //         ->where('game_id', '=', $this->activeGame->id)
        //         ->where('prize_id', '=', $this->prizeSelected)
        //         ->update([
        //             'quantity' => $quantity - 1,
        //         ]);
        // }
    }
}


 /*

Function	Description
array()	Creates an array
array_change_key_case()	Changes all keys in an array to lowercase or uppercase
array_chunk()	Splits an array into chunks of arrays
array_column()	Returns the values from a single column in the input array
array_combine()	Creates an array by using the elements from one "keys" array and one "values" array
array_count_values()	Counts all the values of an array
array_diff()	Compare arrays, and returns the differences (compare values only)
array_diff_assoc()	Compare arrays, and returns the differences (compare keys and values)
array_diff_key()	Compare arrays, and returns the differences (compare keys only)
array_diff_uassoc()	Compare arrays, and returns the differences (compare keys and values, using a user-defined key comparison function)
array_diff_ukey()	Compare arrays, and returns the differences (compare keys only, using a user-defined key comparison function)
array_fill()	Fills an array with values
array_fill_keys()	Fills an array with values, specifying keys
array_filter()	Filters the values of an array using a callback function
array_flip()	Flips/Exchanges all keys with their associated values in an array
array_intersect()	Compare arrays, and returns the matches (compare values only)
array_intersect_assoc()	Compare arrays and returns the matches (compare keys and values)
array_intersect_key()	Compare arrays, and returns the matches (compare keys only)
array_intersect_uassoc()	Compare arrays, and returns the matches (compare keys and values, using a user-defined key comparison function)
array_intersect_ukey()	Compare arrays, and returns the matches (compare keys only, using a user-defined key comparison function)
array_key_exists()	Checks if the specified key exists in the array
array_keys()	Returns all the keys of an array
array_map()	Sends each value of an array to a user-made function, which returns new values
array_merge()	Merges one or more arrays into one array
array_merge_recursive()	Merges one or more arrays into one array recursively
array_multisort()	Sorts multiple or multi-dimensional arrays
array_pad()	Inserts a specified number of items, with a specified value, to an array
array_pop()	Deletes the last element of an array
array_product()	Calculates the product of the values in an array
array_push()	Inserts one or more elements to the end of an array
array_rand()	Returns one or more random keys from an array
array_reduce()	Returns an array as a string, using a user-defined function
array_replace()	Replaces the values of the first array with the values from following arrays
array_replace_recursive()	Replaces the values of the first array with the values from following arrays recursively
array_reverse()	Returns an array in the reverse order
array_search()	Searches an array for a given value and returns the key
array_shift()	Removes the first element from an array, and returns the value of the removed element
array_slice()	Returns selected parts of an array
array_splice()	Removes and replaces specified elements of an array
array_sum()	Returns the sum of the values in an array
array_udiff()	Compare arrays, and returns the differences (compare values only, using a user-defined key comparison function)
array_udiff_assoc()	Compare arrays, and returns the differences (compare keys and values, using a built-in function to compare the keys and a user-defined function to compare the values)
array_udiff_uassoc()	Compare arrays, and returns the differences (compare keys and values, using two user-defined key comparison functions)
array_uintersect()	Compare arrays, and returns the matches (compare values only, using a user-defined key comparison function)
array_uintersect_assoc()	Compare arrays, and returns the matches (compare keys and values, using a built-in function to compare the keys and a user-defined function to compare the values)
array_uintersect_uassoc()	Compare arrays, and returns the matches (compare keys and values, using two user-defined key comparison functions)
array_unique()	Removes duplicate values from an array
array_unshift()	Adds one or more elements to the beginning of an array
array_values()	Returns all the values of an array
array_walk()	Applies a user function to every member of an array
array_walk_recursive()	Applies a user function recursively to every member of an array
arsort()	Sorts an associative array in descending order, according to the value
asort()	Sorts an associative array in ascending order, according to the value
compact()	Create array containing variables and their values
count()	Returns the number of elements in an array
current()	Returns the current element in an array
each()	Deprecated from PHP 7.2. Returns the current key and value pair from an array
end()	Sets the internal pointer of an array to its last element
extract()	Imports variables into the current symbol table from an array
in_array()	Checks if a specified value exists in an array
key()	Fetches a key from an array
krsort()	Sorts an associative array in descending order, according to the key
ksort()	Sorts an associative array in ascending order, according to the key
list()	Assigns variables as if they were an array
natcasesort()	Sorts an array using a case insensitive "natural order" algorithm
natsort()	Sorts an array using a "natural order" algorithm
next()	Advance the internal array pointer of an array
pos()	Alias of current()
prev()	Rewinds the internal array pointer
range()	Creates an array containing a range of elements
reset()	Sets the internal pointer of an array to its first element
rsort()	Sorts an indexed array in descending order
shuffle()	Shuffles an array
sizeof()	Alias of count()
sort()	Sorts an indexed array in ascending order
uasort()	Sorts an array by values using a user-defined comparison function and maintains the index association
uksort()	Sorts an array by keys using a user-defined comparison function
usort()	Sorts an array by values using a user-defined comparison function

===============================================================


echo strtoupper(uniqid('TKID'));

array_chunk() : The array_chunk() function splits an array into chunks of new arrays
------------------------------------------------------------------------------------
$cars=array("Volvo","BMW","Toyota","Honda","Mercedes","Opel");
print_r(array_chunk($cars,2));

Result:
Array ( [0] => Array ( [0] => Volvo [1] => BMW ) [1] => Array ( [0] => Toyota [1] => Honda ) [2] => Array ( [0] => Mercedes [1] => Opel ) )

Parameter	Description
array	Required. Specifies the array to use
size	Required. An integer that specifies the size of each chunk
preserve_key	Optional. Possible values:
    true - Preserves the keys
    false - Default. Reindexes the chunk numerically
Technical Details
Return Value:	Returns a multidimensional indexed array, starting with zero, with each dimension containing size elements
===========================================================

The array_column() function returns the values from a single column in the input array.

Syntax
array_column(array, column_key, index_key)
Parameter Values
Parameter	Description
array	Required. Specifies the multi-dimensional array (record-set) to use. As of PHP 7.0, this can also be an array of objects.
column_key	Required. An integer key or a string key name of the column of values to return. This parameter can also be NULL to return complete arrays (useful together with index_key to re-index the array)
index_key	Optional. The column to use as the index/keys for the returned array

Technical Details
Return Value:	Returns an array of values that represents a single column from the input array


$a = array(
  array(
    'id' => 5698,
    'first_name' => 'Peter',
    'last_name' => 'Griffin',
  ),
  array(
    'id' => 4767,
    'first_name' => 'Ben',
    'last_name' => 'Smith',
  ),
  array(
    'id' => 3809,
    'first_name' => 'Joe',
    'last_name' => 'Doe',
  )
);

$last_names = array_column($a, 'last_name');
print_r($last_names);

Output:
Array
(
  [0] => Griffin
  [1] => Smith
  [2] => Doe
)
===========================================================

Definition and Usage
The array_combine() function creates an array by using the elements from one "keys" array and one "values" array.

Note: Both arrays must have equal number of elements!

Syntax
array_combine(keys, values)
Parameter Values
Parameter	Description
keys	Required. Array of keys
values	Required. Array of values
Technical Details
Return Value:	Returns the combined array. FALSE if number of elements does not match

$fname=array("Peter","Ben","Joe");
$age=array("35","37","43");
$c=array_combine($fname,$age);
print_r($c);

Result:
Array ( [Peter] => 35 [Ben] => 37 [Joe] => 43 )

===========================================================
Definition and Usage
The array_count_values() function counts all the values of an array.

Syntax
array_count_values(array)
Parameter Values
Parameter	Description
array	Required. Specifying the array to count values of
Technical Details
Return Value:	Returns an associative array, where the keys are the original array's values, and the values are the number of occurrences

$a=array("A","Cat","Dog","A","Dog");
print_r(array_count_values($a));

Result:
Array ( [A] => 2 [Cat] => 1 [Dog] => 2 )

===========================================================

Definition and Usage
The array_fill() function fills an array with values.

Syntax
array_fill(index, number, value)
Parameter Values
Parameter	Description
index	Required. The first index of the returned array
number	Required. Specifies the number of elements to insert
value	Required. Specifies the value to use for filling the array
Technical Details
Return Value:	Returns the filled array

$a1=array_fill(3,4,"blue");
$b1=array_fill(0,1,"red");
print_r($a1);
echo "<br>";
print_r($b1);

Array ( [3] => blue [4] => blue [5] => blue [6] => blue )
Array ( [0] => red )
===========================================================

Definition and Usage
The array_key_exists() function checks an array for a specified key, and returns true if the key exists and false if the key does not exist.

Tip: Remember that if you skip the key when you specify an array, an integer key is generated, starting at 0 and increases by 1 for each value. (See example below)

Syntax
array_key_exists(key, array)
Parameter Values
Parameter	Description
key	Required. Specifies the key
array	Required. Specifies an array
Technical Details
Return Value:	Returns TRUE if the key exists and FALSE if the key does not exist

$a=array("Volvo"=>"XC90","BMW"=>"X5");
if (array_key_exists("Volvo",$a))
  {
  echo "Key exists!";
  }
else
  {
  echo "Key does not exist!";
  }
?>

Result:
Key exists!

===========================================================

Definition and Usage
The array_keys() function returns an array containing the keys.

Syntax
array_keys(array, value, strict)
Parameter Values
Parameter	Description
array	Required. Specifies an array
value	Optional. You can specify a value, then only the keys with this value are returned
strict	Optional. Used with the value parameter. Possible values:
true - Returns the keys with the specified value, depending on type: the number 5 is not the same as the string "5".
false - Default value. Not depending on type, the number 5 is the same as the string "5".
Technical Details
Return Value:	Returns an array containing the keys

$a=array("Volvo"=>"XC90","BMW"=>"X5","Toyota"=>"Highlander");
print_r(array_keys($a));

Array ( [0] => Volvo [1] => BMW [2] => Toyota )
===========================================================

Definition and Usage
The array_map() function sends each value of an array to a user-made function, and returns an array with new values, given by the user-made function.

Tip: You can assign one array to the function, or as many as you like.

Syntax
array_map(myfunction, array1, array2, array3, ...)
Parameter Values
Parameter	Description
myfunction	Required. The name of the user-made function, or null
array1	Required. Specifies an array
array2	Optional. Specifies an array
array3	Optional. Specifies an array
Technical Details
Return Value:	Returns an array containing the values of array1, after applying the user-made function to each one

function myfunction($num)
{
  return($num*$num);
}

$a=array(1,2,3,4,5);
print_r(array_map("myfunction",$a));

Array ( [0] => 1 [1] => 4 [2] => 9 [3] => 16 [4] => 25 )
===========================================================

Definition and Usage
The array_merge() function merges one or more arrays into one array.

Tip: You can assign one array to the function, or as many as you like.

Note: If two or more array elements have the same key, the last one overrides the others.

Note: If you assign only one array to the array_merge() function, and the keys are integers, the function returns a new array with integer keys starting at 0 and increases by 1 for each value (See example below).

Tip: The difference between this function and the array_merge_recursive() function is when two or more array elements have the same key. Instead of override the keys, the array_merge_recursive() function makes the value as an array.

Syntax
array_merge(array1, array2, array3, ...)
Parameter Values
Parameter	Description
array1	Required. Specifies an array
array2	Optional. Specifies an array
array3,...	Optional. Specifies an array
Technical Details
Return Value:	Returns the merged array

$a1=array("red","green");
$a2=array("blue","yellow");
print_r(array_merge($a1,$a2));

Array ( [0] => red [1] => green [2] => blue [3] => yellow )
===========================================================

Definition and Usage
The array_pad() function inserts a specified number of elements, with a specified value, to an array.

Tip: If you assign a negative size parameter, the function will insert new elements BEFORE the original elements (See example below).

Note: This function will not delete any elements if the size parameter is less than the size of the original array.

Syntax
array_pad(array, size, value)
Parameter Values
Parameter	Description
array	Required. Specifies an array
size	Required. Specifies the number of elements in the array returned from the function
value	Required. Specifies the value of the new elements in the array returned from the function
Technical Details
Return Value:	Returns an array with new elements

$a=array("red","green");
print_r(array_pad($a,5,"blue"));

Array ( [0] => red [1] => green [2] => blue [3] => blue [4] => blue )
===========================================================

Definition and Usage
The array_pop() function deletes the last element of an array.

Syntax
array_pop(array)
Parameter Values
Parameter	Description
array	Required. Specifies an array
Technical Details
Return Value:	Returns the last value of array. If array is empty, or is not an array, NULL will be returned.

$a=array("red","green","blue");
array_pop($a);
print_r($a);

Array ( [0] => red [1] => green )
===========================================================

Definition and Usage
The array_product() function calculates and returns the product of an array.

Syntax
array_product(array)
Parameter Values
Parameter	Description
array	Required. Specifies an array
Technical Details
Return Value:	Returns the product as an integer or float

$a=array(5,5,2,10);
echo(array_product($a));

500
===========================================================

Definition and Usage
The array_push() function inserts one or more elements to the end of an array.

Tip: You can add one value, or as many as you like.

Note: Even if your array has string keys, your added elements will always have numeric keys (See example below).

Syntax
array_push(array, value1, value2, ...)
Parameter Values
Parameter	Description
array	Required. Specifies an array
value1	Optional. Specifies the value to add (Required in PHP versions before 7.3)
value2	Optional. Specifies the value to add
Technical Details
Return Value:	Returns the new number of elements in the array

$a=array("red","green");
array_push($a,"blue","yellow");
print_r($a);

Array ( [0] => red [1] => green [2] => blue [3] => yellow )

===========================================================

Definition and Usage
The array_rand() function returns a random key from an array, or it returns an array of random keys if you specify that the function should return more than one key.

Syntax
array_rand(array, number)
Parameter Values
Parameter	Description
array	Required. Specifies an array
number	Optional. Specifies how many random keys to return
Technical Details
Return Value:	Returns a random key from an array, or an array of random keys if you specify that the function should return more than one key

$a=array("red","green","blue","yellow","brown");
$random_keys=array_rand($a,3);
echo $a[$random_keys[0]]."<br>";
echo $a[$random_keys[1]]."<br>";
echo $a[$random_keys[2]];

red
blue
yellow
===========================================================

Definition and Usage
The array_reduce() function sends the values in an array to a user-defined function, and returns a string.

Note: If the array is empty and initial is not passed, this function returns NULL.

Syntax
array_reduce(array, myfunction, initial)
Parameter Values
Parameter	Description
array	Required. Specifies an array
myfunction	Required. Specifies the name of the function
initial	Optional. Specifies the initial value to send to the function
Technical Details
Return Value:	Returns the resulting value

function myfunction($v1,$v2)
{
return $v1 . "-" . $v2;
}
$a=array("Dog","Cat","Horse");
print_r(array_reduce($a,"myfunction"));

-Dog-Cat-Horse
===========================================================

Definition and Usage
The array_replace() function replaces the values of the first array with the values from following arrays.

Tip: You can assign one array to the function, or as many as you like.

If a key from array1 exists in array2, values from array1 will be replaced by the values from array2. If the key only exists in array1, it will be left as it is (See Example 1 below).

If a key exist in array2 and not in array1, it will be created in array1 (See Example 2 below).

If multiple arrays are used, values from later arrays will overwrite the previous ones (See Example 3 below).

Tip: Use array_replace_recursive() to replace the values of array1 with the values from following arrays recursively.

Syntax
array_replace(array1, array2, array3, ...)
Parameter Values
Parameter	Description
array1	Required. Specifies an array
array2	Optional. Specifies an array which will replace the values of array1
array3,...	Optional. Specifies more arrays to replace the values of array1 and array2, etc. Values from later arrays will overwrite the previous ones.
Technical Details
Return Value:	Returns the replaced array, or NULL if an error occurs

$a1=array("red","green");
$a2=array("blue","yellow");
print_r(array_replace($a1,$a2));

Array ( [0] => blue [1] => yellow )
===========================================================

Definition and Usage
The array_reverse() function returns an array in the reverse order.

Syntax
array_reverse(array, preserve)
Parameter Values
Parameter	Description
array	Required. Specifies an array
preserve	Optional. Specifies if the function should preserve the keys of the array or not. Possible values:
true
false
Technical Details
Return Value:	Returns the reversed array

$a=array("a"=>"Volvo","b"=>"BMW","c"=>"Toyota");
print_r(array_reverse($a));

Array ( [c] => Toyota [b] => BMW [a] => Volvo )
===========================================================

Definition and Usage
The array_search() function search an array for a value and returns the key.

Syntax
array_search(value, array, strict)
Parameter Values
Parameter	Description
value	Required. Specifies the value to search for
array	Required. Specifies the array to search in
strict	Optional. If this parameter is set to TRUE, then this function will search for identical elements in the array. Possible values:
true
false - Default
When set to true, the number 5 is not the same as the string 5 (See example 2)
Technical Details
Return Value:	Returns the key of a value if it is found in the array, and FALSE otherwise. If the value is found in the array more than once, the first matching key is returned.

$a=array("a"=>"red","b"=>"green","c"=>"blue");
echo array_search("red",$a);
?>

Result:
a

===========================================================

Definition and Usage
The array_shift() function removes the first element from an array, and returns the value of the removed element.

Note: If the keys are numeric, all elements will get new keys, starting from 0 and increases by 1 (See example below).

Syntax
array_shift(array)
Parameter Values
Parameter	Description
array	Required. Specifies an array
Technical Details
Return Value:	Returns the value of the removed element from an array, or NULL if the array is empty

$a=array("a"=>"red","b"=>"green","c"=>"blue");
echo array_shift($a)."<br>";
print_r ($a);

red
Array ( [b] => green [c] => blue )
===========================================================

Definition and Usage
The array_splice() function removes selected elements from an array and replaces it with new elements. The function also returns an array with the removed elements.

Tip: If the function does not remove any elements (length=0), the replaced array will be inserted from the position of the start parameter (See Example 2).

Note: The keys in the replaced array are not preserved.

Syntax
array_splice(array, start, length, array)
Parameter Values
Parameter	Description
array	Required. Specifies an array
start	Required. Numeric value. Specifies where the function will start removing elements. 0 = the first element. If this value is set to a negative number, the function will start that far from the last element. -2 means start at the second last element of the array.
length	Optional. Numeric value. Specifies how many elements will be removed, and also length of the returned array. If this value is set to a negative number, the function will stop that far from the last element. If this value is not set, the function will remove all elements, starting from the position set by the start-parameter.
array	Optional. Specifies an array with the elements that will be inserted to the original array. If it's only one element, it can be a string, and does not have to be an array.
Technical Details
Return Value:	Returns the array consisting of the extracted elements

$a1=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
$a2=array("a"=>"purple","b"=>"orange");
array_splice($a1,0,2,$a2);
print_r($a1);

Array ( [0] => purple [1] => orange [c] => blue [d] => yellow )

===========================================================

Definition and Usage
The array_sum() function returns the sum of all the values in the array.

Syntax
array_sum(array)
Parameter Values
Parameter	Description
array	Required. Specifies an array
Technical Details
Return Value:	Returns the sum of all the values in an array

$a=array(5,15,25);
echo array_sum($a);

45

===========================================================

Definition and Usage
The array_udiff() function compares the values of two or more arrays, and returns the differences.

Note: This function uses a user-defined function to compare the values!

This function compares the values of two (or more) arrays, and return an array that contains the entries from array1 that are not present in array2 or array3, etc.

Syntax
array_udiff(array1, array2, array3, ..., myfunction)
Parameter Values
Parameter	Description
array1	Required. The array to compare from
array2	Required. An array to compare against
array3,...	Optional. More arrays to compare against
myfunction	Required. A string that define a callable comparison function. The comparison function must return an integer <, =, or > than 0 if the first argument is <, =, or > than the second argument
Technical Details
Return Value:	Returns an array containing the entries from array1 that are not present in any of the other arrays

function myfunction($a,$b)
{
if ($a===$b)
  {
  return 0;
  }
  return ($a>$b)?1:-1;
}

$a1=array("a"=>"red","b"=>"green","c"=>"blue");
$a2=array("a"=>"blue","b"=>"black","e"=>"blue");

$result=array_udiff($a1,$a2,"myfunction");
print_r($result);

Array ( [a] => red [b] => green )

===========================================================

Definition and Usage
The array_unique() function removes duplicate values from an array. If two or more array values are the same, the first appearance will be kept and the other will be removed.

Note: The returned array will keep the first array item's key type.

Syntax
array_unique(array, sorttype)
Parameter Values
Parameter	Description
array	Required. Specifying an array
sorttype	Optional. Specifies how to compare the array elements/items. Possible values:
SORT_STRING - Default. Compare items as strings
SORT_REGULAR - Compare items normally (don't change types)
SORT_NUMERIC - Compare items numerically
SORT_LOCALE_STRING - Compare items as strings, based on current locale
Technical Details
Return Value:	Returns the filtered array

$a=array("a"=>"red","b"=>"green","c"=>"red");
print_r(array_unique($a));

Array ( [a] => red [b] => green )

===========================================================

Definition and Usage
The array_unshift() function inserts new elements to an array. The new array values will be inserted in the beginning of the array.

Tip: You can add one value, or as many as you like.

Note: Numeric keys will start at 0 and increase by 1. String keys will remain the same.

Syntax
array_unshift(array, value1, value2, value3, ...)
Parameter Values
Parameter	Description
array	Required. Specifying an array
value1	Optional. Specifies a value to insert (Required in PHP versions before 7.3)
value2	Optional. Specifies a value to insert
value3	Optional. Specifies a value to insert
Technical Details
Return Value:	Returns the new number of elements in the array

$a=array("a"=>"red","b"=>"green");
array_unshift($a,"blue");
print_r($a);

Array ( [0] => blue [a] => red [b] => green )

===========================================================

Definition and Usage
The array_values() function returns an array containing all the values of an array.

Tip: The returned array will have numeric keys, starting at 0 and increase by 1.

Syntax
array_values(array)
Parameter Values
Parameter	Description
array	Required. Specifying an array
Technical Details
Return Value:	Returns an array containing all the values of an array

$a=array("Name"=>"Peter","Age"=>"41","Country"=>"USA");
print_r(array_values($a));

Array ( [0] => Peter [1] => 41 [2] => USA )

===========================================================

Definition and Usage
The array_walk() function runs each array element in a user-defined function. The array's keys and values are parameters in the function.

Note: You can change an array element's value in the user-defined function by specifying the first parameter as a reference: &$value (See Example 2).

Tip: To work with deeper arrays (an array inside an array), use the array_walk_recursive() function.

Syntax
array_walk(array, myfunction, parameter...)
Parameter Values
Parameter	Description
array	Required. Specifying an array
myfunction	Required. The name of the user-defined function
parameter,...	Optional. Specifies a parameter to the user-defined function. You can assign one parameter to the function, or as many as you like
Technical Details
Return Value:	Returns TRUE on success or FALSE on failure

function myfunction($value,$key)
{
echo "The key $key has the value $value<br>";
}
$a=array("a"=>"red","b"=>"green","c"=>"blue");
array_walk($a,"myfunction");

The key a has the value red
The key b has the value green
The key c has the value blue

More Example:
function myfunction($value,$key,$p)
{
echo "$key $p $value<br>";
}
$a=array("a"=>"red","b"=>"green","c"=>"blue");
array_walk($a,"myfunction","has the value");

a has the value red
b has the value green
c has the value blue


===========================================================

Definition and Usage
The array_walk_recursive() function runs each array element in a user-defined function. The array's keys and values are parameters in the function. The difference between this function and the array_walk() function is that with this function you can work with deeper arrays (an array inside an array).

Syntax
array_walk_recursive(array, myfunction, parameter...)
Parameter Values
Parameter	Description
array	Required. Specifying an array
myfunction	Required. The name of the user-defined function
parameter,...	Optional. Specifies a parameter to the user-defined function. You can assign one parameter to the function, or as many as you like.
Technical Details
Return Value:	Returns TRUE on success or FALSE on failure

function myfunction($value,$key)
{
echo "The key $key has the value $value<br>";
}
$a1=array("a"=>"red","b"=>"green");
$a2=array($a1,"1"=>"blue","2"=>"yellow");
array_walk_recursive($a2,"myfunction");

The key a has the value red
The key b has the value green
The key 1 has the value blue
The key 2 has the value yellow

===========================================================

Definition and Usage
The in_array() function searches an array for a specific value.

Note: If the search parameter is a string and the type parameter is set to TRUE, the search is case-sensitive.

Syntax
in_array(search, array, type)
Parameter Values
Parameter	Description
search	Required. Specifies the what to search for
array	Required. Specifies the array to search
type	Optional. If this parameter is set to TRUE, the in_array() function searches for the search-string and specific type in the array.
Technical Details
Return Value:	Returns TRUE if the value is found in the array, or FALSE otherwise

$people = array("Peter", "Joe", "Glenn", "Cleveland");

if (in_array("Glenn", $people))
  {
  echo "Match found";
  }
else
  {
  echo "Match not found";
  }


Match found


===========================================================

Definition and Usage
The key() function returns the element key from the current internal pointer position.

This function returns FALSE on error.

Syntax
key(array)
Parameter Values
Parameter	Description
array	Required. Specifies the array to use
Technical Details
Return Value:	Returns the key of the array element that is currently being pointed to by the internal pointer

$people=array("Peter","Joe","Glenn","Cleveland");
echo "The key from the current position is: " . key($people);

The key from the current position is: 0

===========================================================



===========================================================



===========================================================



===========================================================



===========================================================



===========================================================



===========================================================



===========================================================



===========================================================

*/
