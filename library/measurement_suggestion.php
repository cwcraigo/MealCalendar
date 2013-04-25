<?php

session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/model.php';

//set incoming string to local variable
$q = $_GET["q"];

$result = array();

//get result from function from model
$result = measurement_suggestions($q);

if (empty($q)) {
    $response = "Could not get q.";
} else {
    if (empty($result)) {
        $response = "Could not get Recipe.";
    }
    $response = $rec;
}
//output the response
echo '<b>Measurement Suggestions</b><br />';
foreach ($result as $response) {
    echo $response.'<br />';
}
?>