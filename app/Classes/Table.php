<?php

namespace App\Classes;

/**
 * Class Table
 *
 * - One table consist of 6 tickets.
 * - It must have all numbers from 1 to 90, and they must be used only once
 *   + 1st column, numbers from 1 to 9
 *   + 2nd-8th column, numbers from 20-29, 30-39 ... 80-89
 *   + 9th column numbers from 80 to 90
 * - each row has exactly 5 numbers
 * - each column must have at least one number
 *
 * @package Ivebe\Tombola
 */
class Table
{
    private $tickets;

    public function __construct()
    {

        //Why initialization in constructor? Because I decided to follow YAGNI.
        for($i=0; $i<6; $i++)
             $this->tickets[$i] = new Ticket;
    }

    // private function generateBuckets()
    // {
    //     $bucket[0] = range(1,9);   shuffle($bucket[0]);
    //     $bucket[1] = range(10,19); shuffle($bucket[1]);
    //     $bucket[2] = range(20,29); shuffle($bucket[2]);
    //     $bucket[3] = range(30,39); shuffle($bucket[3]);
    //     $bucket[4] = range(40,49); shuffle($bucket[4]);
    //     $bucket[5] = range(50,59); shuffle($bucket[5]);
    //     $bucket[6] = range(60,69); shuffle($bucket[6]);
    //     $bucket[7] = range(70,79); shuffle($bucket[7]);
    //     $bucket[8] = range(80,90); shuffle($bucket[8]);

    //     return $bucket;
    // }

    private function generateBuckets()
    {
        $buckets = [];

        $buckets[0] = range(1, 9); shuffle($buckets[0]);
        $buckets[1] = range(10, 19); shuffle($buckets[1]);
        $buckets[2] = range(20, 29); shuffle($buckets[2]);
        $buckets[3] = range(30, 39); shuffle($buckets[3]);
        $buckets[4] = range(40, 49); shuffle($buckets[4]);
        $buckets[5] = range(50, 59); shuffle($buckets[5]);
        $buckets[6] = range(60, 69); shuffle($buckets[6]);
        $buckets[7] = range(70, 79); shuffle($buckets[7]);
        $buckets[8] = range(80, 90); shuffle($buckets[8]);

        // Ensure each bucket has at least one element
        foreach ($buckets as $bucket) {
            if (empty($bucket)) {
                $bucket[] = 1; // Add a default value if the bucket is empty
            }
        }

        return $buckets;
    }

    private function distribute($buckets)
    {
        // 1st iteration
        foreach ($this->tickets as $ticket) {
            for ($k = 0; $k < 9; $k++) {
                $ticket->addToBucket($k, array_pop($buckets[$k]));
            }
        }

        // Add elements from the last bucket to random tickets
        while (!empty($buckets[8]) && !empty($this->tickets)) {
            $randomIndex = array_rand($this->tickets);
            $lastBucketElement = array_pop($buckets[8]);
            $this->tickets[$randomIndex]->addToBucket(8, $lastBucketElement);
        }

        // Additional passes for max 2 or 3 numbers per column
        for ($i = 0; $i < 3; $i++) {
            for ($k = 0; $k < 9; $k++) {
                // If bucket is empty, skip
                if (empty($buckets[$k])) continue;

                foreach ($this->tickets as $ticket) {
                    if ($ticket->bucketCount() < 15 && count($ticket->getBucket($k)) < 2) {
                        $ticket->addToBucket($k, array_pop($buckets[$k]));
                        break; // Move to the next column
                    }
                }
            }
        }

        // Last pass for max 3 numbers per column
        for ($k = 0; $k < 9; $k++) {
            // If bucket is empty, skip
            if (empty($buckets[$k])) continue;

            foreach ($this->tickets as $ticket) {
                if ($ticket->bucketCount() < 15 && count($ticket->getBucket($k)) < 3) {
                    $ticket->addToBucket($k, array_pop($buckets[$k]));
                    break; // Move to the next column
                }
            }
        }

        // Sort buckets in tickets
        foreach ($this->tickets as $ticket) {
            $ticket->sortBuckets();
            $ticket->distribute();
            $ticket->fillBlanks();
        }
    }

    // public function generate()
    // {
    //     $buckets = $this->generateBuckets();
    //     $this->distribute($buckets);
        
    //     // Assign a random ticketId to each ticket
    //     foreach ($this->tickets as $ticket) {
    //         $ticket->setTicketId($this->random_string(8));
    //     }

    //     // If the number of tickets is different from 6, remove extra tickets
    //     // $this->tickets = array_slice($this->tickets, 0, 2);

    //     for ($i = 0; $i < 3; $i++) {
    //         for ($k = 0; $k < 9; $k++) {
    //             if (isset($ticket->numbers[$i][$k]['value'])) {
    //                 $value = $ticket->numbers[$i][$k]['value'];
    //                 $ticket->numbers[$i][$k]['id'] = $value;
    //             }
    //         }
    //     }

    // }

    public function generate()
    {
        $buckets = $this->generateBuckets($numberOfTickets = 6);
        $this->distribute($buckets);

        // Assign a random ticketId to each ticket
        foreach ($this->tickets as $ticket) {
            $ticket->setTicketId($this->random_string(8));

            // Set 'id' based on the 'value' in numbers array
            for ($i = 0; $i < 3; $i++) {
                for ($k = 0; $k < 9; $k++) {
                    if (isset($ticket->numbers[$i][$k]['value'])) {
                        $value = $ticket->numbers[$i][$k]['value'];
                        $ticket->numbers[$i][$k]['id'] = $i.''.$k;
                    }
                }
            }
        }

        // If the number of tickets is different from 6, remove extra tickets
        $this->tickets = array_slice($this->tickets, 0, $numberOfTickets);
    }


    public function getTickets()
    {
        return $this->tickets;
    }

    public function prettyPrint()
    {
        foreach($this->tickets as $ticket)
            $ticket->prettyPrint();
    }

    public function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
    
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
    
        return strtoupper($key);
    }
    
}


// In the provided code, the term "bucket" is used to represent an array that holds numbers in a certain range. The Table class generates bingo tickets, and the Ticket class contains these buckets to organize the distribution of numbers onto the tickets.

// Here's a breakdown of the key elements related to the term "bucket":

// $buckets array in Table class:

// The generateBuckets method creates an array of 9 buckets, each corresponding to a specific range of numbers (e.g., 1-9, 10-19, 20-29, ..., 80-90).
// Each bucket is initially filled with numbers in its respective range, and then the elements are shuffled.
// $bucket property in Ticket class:

// Each instance of the Ticket class has a private property $bucket, which is an array with 9 elements.
// During the distribution process in the distribute method, numbers are added to these buckets based on certain criteria.
// addToBucket method in Ticket class:

// This method is responsible for adding a number to a specific bucket within a Ticket instance.
// In the modified version of the code, the numbers are stored as associative arrays with 'checked' and 'value' keys in the buckets.
// The purpose of using buckets is to organize the distribution of numbers onto the bingo tickets in a controlled manner. Numbers from each bucket are assigned to tickets based on certain rules outlined in the distribute method. This helps ensure that the generated tickets follow specific patterns or constraints, creating a well-formed set of bingo tickets.
