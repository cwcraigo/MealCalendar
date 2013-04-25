<?php 

session_start();

/***********************************************************
 * Library of helper functions.
 **********************************************************/

// Validate for strings function.
function string_check($str) {
    $str = filter_var($str, FILTER_SANITIZE_STRING);
    return $str;
}

// Validate for numbers function.
function number_check($num) {
    // Sanitize or takes everything but a number out.
    $num = filter_var($num, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    // Validate or makes sure it is a number.
    $num = filter_var($num, FILTER_VALIDATE_FLOAT);
    return $num;
}

// Validate email address function
function email_check($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $email;
}

?>
