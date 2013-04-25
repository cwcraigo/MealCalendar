<?php
session_start();

if ($_SESSION['client_id'] && $_SESSION['client_first'] && $_SESSION['client_last']) {
    $client_id = $_SESSION['client_id'];
    $fname = $_SESSION['client_first'];
    $lname = $_SESSION['client_last'];
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Meal Calendar</title>
        <link href="http://mealcalendar.cwcraigo.com/calendar_style.css" rel="stylesheet" type="text/css" media="screen" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
        <script type="text/javascript" src="/view/inventory.js"></script>
    </head>
    <body>
        <div id="header">
            <h1 id="main_title">Kimberly's Kalendar</h1>
            <?php
            if ($_SESSION['loggedin']) {
                echo '<div id="welcome">
                            <p>Welcome ' . $_SESSION['client_first'] . ' ' . $_SESSION['client_last'] . ' <span id="line">|</span> <a href="/index.php?action=logout">Logout</a></p>
                        </div>
                        <br />
                    <ul id="nav_links">
                    <li><a href="/home.php">HOME</a> | </li>
                    <li><a href="/calendar/calendar.php">Calendar</a> | </li>
                    <li><a href="/shopping_list/shopping_list.php">Shopping List</a> | </li>
                    <li><a href="/?action=back_meals" >Meals</a> | </li>
                    <li><a href="/?action=add_recipe" >Add Recipe</a> | </li>
                    <li><a href="/?action=inventory" >Ingredients on Hand</a></li>
                    </ul></div>';
            } else {
                echo '</div><div id="login">
                        <p>Click here to <a href="/login_reg/reg.php">Register</a> or here to <a href="/login_reg/login.php">Login.</a></p>
                    </div>';
            }
            ?>