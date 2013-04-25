<?php

session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/model.php';

$q = $_GET["q"];

$_SESSION['rec_id'] = $q;

$rec = print_recipe($q);

if (empty($rec)) {

    $response = "Could not get Recipe.";
}

if (empty($q)) {

    $response = "Could not get q.";
} else {

    $response = $rec;
}

//output the response
echo $response;
include $_SERVER['DOCUMENT_ROOT'] . '/modules/close_button.php';
?>