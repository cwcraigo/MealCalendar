<?php

session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/library/lib.php';
require $_SERVER['DOCUMENT_ROOT'] . '/model.php';

if ($_POST['action']) {
    $action = $_POST['action'];
} else {
    $action = $_GET['action'];
}

switch ($action) {

    case 'Keith':

        header('Location: keith.php');
        exit;
        break;

    case 'Search Recipes':
        $field_num = $_POST['field_num'];

        $inventory = array();
        for ($i = 0; $i < $field_num; $i++) {
            if ($_POST['dynamic' . $i] != '') {
                $inventory[] = string_check($_POST['dynamic' . $i]);
            }
        }

        if (!empty($inventory)) {
            $result = get_recipe_with_ingredient($inventory);
            unset($inventory);
        } else {
            $result = 'Please list your inventory.';
        }

        $_SESSION['inventory_result'] = $result;

        header('Location: /view/inventory.php');
        exit;
        break;

    case 'inventory':
        header('Location: /view/inventory.php');
        exit;
        break;

    case 'Edit Recipe':

        $match_counter = 0;

        $recipie_id = number_check($_POST['id']);
        $ing_num = number_check($_POST['ing_num']);
        $recipe_name = string_check($_POST['recipie_name']);
        $author = string_check($_POST['author']);
        $instructions = string_check($_POST['instructions']);
        $created_by = $_SESSION['client_id'];

        //$ing_num = $_SESSION['ing_num'];
        $q_array = array();
        $m_array = array();
        $p_array = array();
        $ingredients_array = array();

        if (empty($recipe_name) || empty($author) || empty($instructions)) {
            $r_message = 'All fields are required';
            $_SESSION['r_message'] = $r_message;
            header('Location: /view/edit_recipe.php');
            exit;
        } else {

            $match = match_recipe($recipie_id, $recipe_name, $instructions, $created_by, $author);

            if (!$match) {
                $ed_rec = edit_recipie($recipe_name, $instructions, $created_by, $author, $recipie_id);
            } else {
                $match_counter++;
            }

            for ($i = 1; $i <= $ing_num; $i++) {

                $id = number_check($_POST['ing_id' . $i]);
                $q = number_check($_POST['quantity' . $i]);
                $m = string_check($_POST['measurement' . $i]);
                $p = string_check($_POST['product' . $i]);

                $i_match = match_ingredients($q, $m, $p, $recipie_id);

                if ($i_match == 0) {
                    $i_array['id'] = $id;
                    $i_array['q'] = $q;
                    $i_array['m'] = $m;
                    $i_array['p'] = $p;
                    $ingredients_array[] = $i_array;
                }
            }

            if (!empty($ingredients_array)) {

                $result = edit_ingredients($ingredients_array);

                if ($result == 0) {
                    $r_message = 'Could not edit ingredients.';
                    $_SESSION['r_message'] = $r_message;
                    header('Location: /view/edit_recipe.php');
                    exit;
                }
            } else {
                $match_counter = $match_counter + 2;
            }

            if ($match_counter == 0) {
                $r_message = 'Updated recipe and ingredients Success!';
            } elseif ($match_counter == 2) {
                $r_message = 'Update recipe Success!';
            } elseif ($match_counter == 1) {
                $r_message = 'Update ingredients Success!';
            } else {
                $r_message = 'Update Success! (Though you didn\'t even change anything.)';
            }
        }
        $_SESSION['r_message'] = $r_message;
        header('Location: /view/meals.php');
        exit;
        break;

    case 'Edit':

        $id = $_GET['id'];
        $_SESSION['ed_rec_id'] = $id;

        $rec = array();
        $rec = get_recipe($id);
        $_SESSION['er_name'] = $rec['name'];
        $_SESSION['er_instructions'] = $rec['instruction'];
        $_SESSION['er_author'] = $rec['author'];
        $_SESSION['er_created_by'] = $rec['created_by'];

        $ingredients = array();
        $m_array = array();
        $ing = array();
        $ing_array = array();
        $ingredients = get_recipe_ingredients($id);

        //id,recipie_id,quantity,measurement(id->get_measurment_sym),prod(id->get_prod_name)
        foreach ($ingredients as $i) {
            $ing['ing_id'] = $i['id'];
            $ing['quantity'] = $i['quantity'];
            $m_array = get_measurement_symbol($i['measurement']);
            $ing['measurement_id'] = $i['measurement'];
            $ing['measurement_name'] = $m_array['name'];
            $ing['symbol'] = $m_array['symbol'];
            $ing['product_id'] = $i['prod'];
            $ing['product'] = get_product_name($i['prod']);
            $ing_array[] = $ing;
        }

        $_SESSION['ed_ing_array'] = $ing_array;

        $measure_array = array();
        $measure_array = get_all_measurements();
        $_SESSION['measure_array'] = $measure_array;

        $prod_array = array();
        $prod_array = get_all_products();
        $_SESSION['prod_array'] = $prod_array;

        header('Location: /view/edit_recipe.php');
        exit;
        break;

    case 'Generate Shopping List':

        $year = $_POST['year'];
        $month = $_POST['month'];
        $client_id = $_SESSION['client_id'];

        $final = array();
        $final = get_monthly_shoppinglist($year, $month, $client_id);

        $_SESSION['shopping_list'] = $final;
        header('Location: /shopping_list/shopping_list.php');
        exit;
        break;

    case 'meals':

        $day = $_GET['day'];
        $month = $_GET['month'];
        $year = $_GET['year'];

        $date = array();
        $date['day'] = $day;
        $date['month'] = $month;
        $date['year'] = $year;
        $_SESSION['date'] = $date;

        $recipe_array = array();
        $recipe_array = get_all_recipies();

        $c_array = array();
        $c_array = print_categories();
        $_SESSION['c_array'] = $c_array;

        $_SESSION['recipe_array'] = $recipe_array;

        $client_id = $_SESSION['client_id'];

        $calendar_id = check_client_calendar($client_id, $year, $month, $day);

        $bfast_id = get_meal_recipe_id('breakfast', $calendar_id);
        $bfast = get_recipe_name($bfast_id);
        $lunch_id = get_meal_recipe_id('lunch', $calendar_id);
        $lunch = get_recipe_name($lunch_id);
        $dinner_id = get_meal_recipe_id('dinner', $calendar_id);
        $dinner = get_recipe_name($dinner_id);
        $snacks_id = get_meal_recipe_id('snacks', $calendar_id);
        $snacks = get_recipe_name($snacks_id);

        $meals_array = array();
        $meals_array['bfast_id'] = $bfast_id;
        $meals_array['bfast_name'] = $bfast;
        $meals_array['lunch_id'] = $lunch_id;
        $meals_array['lunch_name'] = $lunch;
        $meals_array['dinner_id'] = $dinner_id;
        $meals_array['dinner_name'] = $dinner;
        $meals_array['snacks_id'] = $snacks_id;
        $meals_array['snacks_name'] = $snacks;

        $_SESSION['meals'] = $meals_array;

        header('Location: /view/meals.php');
        exit;
        break;

    case 'Apply Meals':
        $message = array();

        $bfast = number_check($_POST['bfast']);
        $lunch = number_check($_POST['lunch']);
        $dinner = number_check($_POST['dinner']);
        $snacks = number_check($_POST['snack']);

        $client_id = $_SESSION['client_id'];

        $date = $_SESSION['date'];
        $day = $date['day'];
        $month = $date['month'];
        $year = $date['year'];

        // check if need to update or insert.
        $cal_id = check_client_calendar($client_id, $year, $month, $day);

        // if nothing in db for this day then insert into calendar and insert into meals
        if ($cal_id == 0) {

            $result = add_calendar($year, $month, $day, $client_id);

            if ($result == 0) {
                $message[] = 'Insert into Calendar failed.';
            } else {
                $message[] = 'Insert Calendar Success!';

                $cal_id = check_client_calendar($client_id, $year, $month, $day);

                if ($cal_id) {

                    if (!empty($bfast)) {
                        $result1 = add_meal('breakfast', $cal_id, $bfast);
                        if ($result1 == 0) {
                            $message[] = 'Insert into Breakfast failed.';
                        } else {
                            $message[] = 'Insert Breakfast Success!';
                        }
                    }
                    if (!empty($lunch)) {
                        $result2 = add_meal('lunch', $cal_id, $lunch);
                        if ($result2 == 0) {
                            $message[] = 'Insert into Lunch failed.';
                        } else {
                            $message[] = 'Insert Lunch Success!';
                        }
                    }
                    if (!empty($dinner)) {
                        $result3 = add_meal('dinner', $cal_id, $dinner);
                        if ($result3 == 0) {
                            $message[] = 'Insert into Dinner failed.';
                        } else {
                            $message[] = 'Insert Dinner Success!';
                        }
                    }
                    if (!empty($snacks)) {
                        $result4 = add_meal('snacks', $cal_id, $snacks);
                        if ($result4 == 0) {
                            $message[] = 'Insert into Snacks failed.';
                        } else {
                            $message[] = 'Insert Snacks Success!';
                        }
                    }
                } else {
                    $message[] = 'Critical error. Please try again.';
                }
            }
        } else {

            if (!empty($bfast)) {
                $meal_id = get_meal_id('breakfast', $cal_id);
                if ($meal_id == NULL) {
                    $result1 = add_meal('breakfast', $cal_id, $bfast);
                    if ($result1 == 0) {
                        $message[] = 'Insert into Breakfast failed.';
                    } else {
                        $message[] = 'Insert Breakfast Success!';
                    }
                } else {
                    $result1 = edit_meal('breakfast', $cal_id, $bfast);
                    if ($result1 == 0) {
                        $message[] = 'Update Breakfast failed.';
                    } else {
                        $message[] = 'Update Breakfast Success!';
                    }
                }
            }

            if (!empty($lunch)) {
                $meal_id = get_meal_id('lunch', $cal_id);
                if ($meal_id == NULL) {
                    $result2 = add_meal('lunch', $cal_id, $lunch);
                    if ($result2 == 0) {
                        $message[] = 'Insert into Lunch failed.';
                    } else {
                        $message[] = 'Insert Lunch Success!';
                    }
                } else {
                    $result2 = edit_meal('lunch', $cal_id, $lunch);
                    if ($result2 == 0) {
                        $message[] = 'Update Lunch failed.';
                    } else {
                        $message[] = 'Update Lunch Success!';
                    }
                }
            }

            if (!empty($dinner)) {
                $meal_id = get_meal_id('dinner', $cal_id);
                if ($meal_id == NULL) {
                    $result3 = add_meal('dinner', $cal_id, $dinner);
                    if ($result3 == 0) {
                        $message[] = 'Insert into Dinner failed.';
                    } else {
                        $message[] = 'Insert Dinner Success!';
                    }
                } else {
                    $result3 = edit_meal('dinner', $cal_id, $dinner);
                    if ($result3 == 0) {
                        $message[] = 'Update Dinner failed.';
                    } else {
                        $message[] = 'Update Dinner Success!';
                    }
                }
            }

            if (!empty($snacks)) {
                $meal_id = get_meal_id('snacks', $cal_id);
                if ($meal_id == NULL) {
                    $result4 = add_meal('snacks', $cal_id, $snacks);
                    if ($result4 == 0) {
                        $message[] = 'Insert into Snacks failed.';
                    } else {
                        $message[] = 'Insert Snacks Success!';
                    }
                } else {
                    $result4 = edit_meal('snacks', $cal_id, $snacks);
                    if ($result4 == 0) {
                        $message[] = 'Update Snacks failed.';
                    } else {
                        $message[] = 'Update Snacks Success!';
                    }
                }
            }
        }

        $_SESSION['message'] = $message;
        header('Location: /view/meals.php');
        exit;
        break;

    case 'cal_back':
        header('Location: /calendar/calendar.php');
        exit;
        break;


    case 'back_meals':
        $recipe_array = array();
        $recipe_array = get_all_recipies();

        $_SESSION['recipe_array'] = $recipe_array;

        header('Location: /view/meals.php');
        exit;
        break;


    case 'add_recipe':
        $measure_array = array();
        $prod_array = array();
        $category_array = array();
        $measure_array = get_all_measurements();
        $prod_array = get_all_products();
        $category_array = get_all_categories();

        $_SESSION['measure_array'] = $measure_array;
        $_SESSION['prod_array'] = $prod_array;
        $_SESSION['category_array'] = $category_array;

        header('Location: /add/add_recipie.php');
        exit;
        break;

    case 'add_measure':
        header('Location: /add/add_measurement.php');
        exit;
        break;


    case 'add_product':
        header('Location: /add/add_product.php');
        exit;
        break;


    case 'Add Measurement':
        $measurement_name = string_check($_POST['measurement_name']);
        $measurement_symbol = string_check($_POST['measurement_symbol']);
        if (empty($measurement_name) || empty($measurement_symbol)) {
            $m_message = 'All fields are required';
            $_SESSION['m_message'] = $m_message;
            header('Location: /add/add_measurement.php');
            exit;
        } else {
            $m = add_measurement($measurement_name, $measurement_symbol);
            if ($m) {
                $m_message = 'Insert Success!';
                $_SESSION['m_message'] = $m_message;
                header('Location: /add/add_measurement.php');
                exit;
            } else {
                $m_message = 'Insert Failure!';
                $_SESSION['m_message'] = $m_message;
                header('Location: /add/add_measurement.php');
                exit;
            }
        }
        break;

    case 'Add Product':
        $prod_name = string_check($_POST['product_name']);
        if (empty($prod_name)) {
            $p_message = 'All fields are required';
            $_SESSION['p_message'] = $p_message;
        } else {
            $p = add_product($prod_name);
            if ($p == 1) {
                $p_message = 'Insert Success!';
                $_SESSION['p_message'] = $p_message;
            } else {
                $p_message = 'Insert Failure!';
                $_SESSION['p_message'] = $p_message;
            }
        }
        header('Location: /add/add_product.php');
        exit;
        break;

    case 'Add Recipe':
        $recipie_name = string_check($_POST['recipie_name']);
        $author = string_check($_POST['author']);
        $instructions = string_check($_POST['instructions']);
        $category = number_check($_POST['category_num']);
        $sub_category = number_check($_POST['sub_category']);
        $created_by = $_SESSION['client_id'];

        $ing_num = $_SESSION['field_num'];
        $q_array = array();
        $m_array = array();
        $p_array = array();
        $ingredients_array = array();

        if (empty($recipie_name) || empty($author) || empty($instructions) || empty($sub_category)) {
            $r_message = 'All fields are required';
            $_SESSION['r_message'] = $r_message;
        } else {

            $rec = add_recipie($instructions, $recipie_name, $created_by, $author, $category, $sub_category);

            if ($rec == 1) {

                $rec_id = get_recipe_id($instructions, $recipie_name, $created_by, $author);

                if ($rec_id == 0) {
                    $r_message = 'Could not get recipie_id';
                    $_SESSION['r_message'] = $r_message;
                    header('Location: /add/add_recipie.php');
                    exit;
                }

                for ($i = 1; $i <= $ing_num; $i++) {
                    $i_array['id'] = $rec_id;
                    $i_array['q'] = number_check($_POST['quantity' . $i]);
                    $i_array['m'] = check_measurement(string_check($_POST['measurement' . $i]));
                    $i_array['p'] = check_product(string_check($_POST['product' . $i]));

                    if (!$i_array['q']) {
                        echo 'Please enter a number into quantity.';
                        exit;
                    }

                    $ingredients_array[] = $i_array;
                }

                $result = add_ingredients($ingredients_array);

                if ($result == 0) {
                    $r_message = 'Could not insert ingredients.';
                    $_SESSION['r_message'] = $r_message;
                    header('Location: /add/add_recipie.php');
                    exit;
                }
            }

            $r_message = 'Insert Success!';
            $_SESSION['r_message'] = $r_message;
        }

        header('Location: /add/add_recipie.php');
        exit;
        break;

    case 'logout':
        session_destroy();
        header('Location: /home.php');
        exit;
        break;

    case 'Generate Calendar':
        $month = $_POST['month'];
        $year = $_POST['year'];

        $_SESSION['cal_month'] = $month;
        $_SESSION['cal_year'] = $year;

        header('Location: /calendar/calendar.php');
        exit;
        break;

    case 'Register':

        $firstname = string_check($_POST['cfirst']);
        $lastname = string_check($_POST['clast']);
        $email = email_check($_POST['cemail']);
        $user_name = string_check($_POST['user_name']);
        $password = string_check($_POST['cpassword']);
        $password2 = string_check($_POST['cpassword2']);

        $errors = array();
        $reg_array = array($firstname, $lastname, $email, $user_name);

        // Validate the data
        if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($password2) || empty($user_name)) {
            $errors[0] = 'All fields are required.';
        }

        if ($password != $password2) {
            $errors[1] = 'Passwords do not match.';
        }

        if (!$email) {
            $errors[2] = 'Must have valid Email.';
        }

        // Check for validation errors, send back if found
        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['reg_array'] = $reg_array;
            header('Location: /login_reg/reg.php');
            exit;
        }

        // Check for success or failure - inform the client
        if (empty($errors)) {

            //masking or hashing password
            $password = sha1($password);

            $success = add_client($firstname, $lastname, $user_name, $password, $email);

            if ($success) {

                $_SESSION['reg_array'] = $reg_array;

                $client_array = get_client($user_name, $password);

                session_regenerate_id(true); // Sets new session, distroys old one
                $_SESSION['loggedin'] = true;
                $_SESSION['client_id'] = $client_array[0]['id'];
                $_SESSION['client_first'] = $client_array[0]['fname'];
                $_SESSION['client_last'] = $client_array[0]['lname'];
                setcookie('username', $client_array[0]['id'], strtotime('+1 year'), '/');
                header('Location: /home.php');
                exit;
            } else {

                $errors[0] = 'Sorry please try again.';
                $_SESSION['errors'] = $errors;
                header('Location: /login_reg/reg.php');
                exit;
            }
        }
        break;

    case 'Login':
        // Clear data before resetting it
        unset($_SESSION['login_error']);

        // Collect data
        $username = string_check($_POST['cusername']);
        $password = string_check($_POST['cpassword']);

        // Set arrays
        $login_error = array();

        // Validate data
        if (empty($username) || empty($password)) {
            $login_error[0] = 'All fields are required.';
        }

        // Check validation error and send back if any
        if ($login_error) {
            $_SESSION['login_error'] = $login_error;
            header('Location: /login_reg/login.php');
            exit;
        }

        // If no errors - send to home view
        if (empty($login_error)) {
            // Hash password
            $password = sha1($password);

            $client = array();
            $client = get_client($username, $password);

            // check if client then return to user
            if (!empty($client)) {
                session_regenerate_id(true); // Sets new session, distroys old one
                $_SESSION['loggedin'] = true;
                $_SESSION['client_id'] = $client[0]['id'];
                $_SESSION['client_first'] = $client[0]['fname'];
                $_SESSION['client_last'] = $client[0]['lname'];
                setcookie('username', $client[0]['id'], strtotime('+1 year'), '/');
                header('Location: /home.php');
                exit;
            } else {
                $login_error[0] = 'Sorry, I cannot log you in.';
                $_SESSION['login_error'] = $login_error;
                header('Location: /login_reg/login.php');
                exit;
            }
        }
        break;

    case 'contact_info':
        header('Location: /view/contact_info.php');
        exit;
        break;

    default:
        header('Location: /home.php');
        exit;
        break;
}
?>