<?php

namespace App\Classes;


class Ticket
{
    public $numbers = [];
    private $bucket  = [];
    private $ticketId;

    public function __construct()
    {
    }
    
    public function addToBucket($k, $v)
    {
        //$this->bucket[$k][] = $v;
        $this->bucket[$k][] = ['id' => 0, 'checked' => 0, 'value' => $v];
        //$this->bucket[$k][] = ['checked' => 0, 'value' => $v, 'ticket_id' => $this->ticketId];
        
    }

    public function bucketCount()
    {
        $sum = 0;
        foreach($this->bucket as $b)
            $sum += count($b);

        return $sum;
    }

    public function sortBuckets()
    {
        for($i=0; $i<9; $i++)
            sort($this->bucket[$i]);
    }

    public function getBucket($i)
    {
        return $this->bucket[$i];
    }

    public function rowCount($row)
    {
        $sum = 0;
        for($j=0; $j<9; $j++)
            if(isset($this->numbers[$row][$j]) && $this->numbers[$row][$j] != 0) $sum++;

        return $sum;
    }

    public function distribute()
    {
        //fill first row
        for($size=3; $size>0; $size--)
        {
            if($this->rowCount(0) == 5) break;

            for($i=0; $i<9; $i++)
            {
                if($this->rowCount(0) == 5) break;
                if(isset($this->numbers[0][$i])) continue;

                if( count($this->bucket[$i]) != $size) continue;

                $this->numbers[0][$i] = array_shift($this->bucket[$i]);
                // $this->numbers[0][$i]['checked'] = 0;
            }
        }

        //fill second row
        for($size=2; $size>0; $size--)
        {
            if($this->rowCount(1) == 5) break;

            for($i=0; $i<9; $i++)
            {
                if($this->rowCount(1) == 5) break;
                if(isset($this->numbers[1][$i])) continue;

                if( count($this->bucket[$i]) != $size) continue;

                $this->numbers[1][$i] = array_shift($this->bucket[$i]);
            }
        }

        //fill third row
        for($size=1; $size>0; $size--)
        {
            if($this->rowCount(2) == 5) break;

            for($i=0; $i<9; $i++)
            {
                if($this->rowCount(2) == 5) break;
                if(isset($this->numbers[2][$i])) continue;

                if( count($this->bucket[$i]) != $size) continue;

                $this->numbers[2][$i] = array_shift($this->bucket[$i]);
                // $this->numbers[2][$i]->value = array_shift($this->bucket[$i]);
                // $this->numbers[2][$i]['meta_checked'] = 0;
            }
        }
    }

    public function fillBlanks()
    {
        for($i=0; $i<3; $i++)
            for($j=0; $j<9; $j++)
                if(!isset($this->numbers[$i][$j]))
                    $this->numbers[$i][$j] = 0;

        //sort
        for($i=0; $i<3; $i++)
            ksort($this->numbers[$i]);
    }

    /**
     * @return array array[3,9] with populated numbers
     */
    public function getNumbers()
    {
        return $this->numbers;
    }

    /**
     * Just for demonstration
     */
    public function prettyPrint()
    {
        echo "<table border='1'>";
        echo "<tr><td colspan='9'>Ticket No: {$this->getTicketId()}</td></tr>";

        for ($i = 0; $i < 3; $i++) {
            echo "<tr>";
            for ($j = 0; $j < 9; $j++) {
                $value = isset($this->numbers[$i][$j]['value']) ? $this->numbers[$i][$j]['value'] : '';
                // Display the value in each cell
                $char = $value != 0 ? "{$value}" : '';
                echo "<td width='30' height='30'>{$value}</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    public function getTicketId()
    {
        return $this->ticketId;
    }

    public function setTicketId($ticketId)
    {
        $this->ticketId = $ticketId;
    }
}