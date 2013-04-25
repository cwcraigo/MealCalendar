<?php

function odd_even($x) 
{
    
    if ($x == 1 || $x == 3 || $x == 5) {
        $y = 'odd';
    } else {
        $y = 'even';
    }
    
    echo $y;
}

$x = 2;

$result = odd_even($x);

echo $result;

?>
