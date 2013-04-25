<?php

session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/DBconns/iUserConn.php';
require $_SERVER['DOCUMENT_ROOT'] . '/DBconns/iAdminConn.php';

/* * ***************************************************************************
 * *****************************************************************************
 * ************************** RECIPE FUNCTIONS *********************************
 * *****************************************************************************
 * ************************************************************************** */

// ----------------------------------------------------------------------------- SELECT RECIPE FUNCTIONS
function match_recipe($recipe_id, $recipe_name, $instruction, $created_by, $author) {
    global $iUserConn;
    global $db;

    $sql = "SELECT COUNT(*)
            FROM $db.recipies
            WHERE recipie_id = ?
            AND recipie_name = ?
            AND instruction = ?
            AND created_by = ?
            AND author = ?";

    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('issis', $recipe_id, $recipe_name, $instruction, $created_by, $author);
        $stmt->bind_result($count);
        $stmt->execute();
        while ($stmt->fetch()) {
            $count = $count;
        }
        $stmt->close();
    } else {
        echo 'Match recipe stmt didnt prepare.';
        exit;
    }

    if ($count == 0) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function get_recipe_name($recipe_id) {
    global $iUserConn;
    global $db;

    $sql = "SELECT recipie_name FROM $db.recipies WHERE recipie_id='$recipe_id'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($name);
        $stmt->execute();
        while ($stmt->fetch()) {
            $name = $name;
        }
        $stmt->close();
    } else {
        echo 'Get recipie name stmt didnt prepare.';
        exit;
    }

    if (!empty($name)) {
        return $name;
    } else {
        return NULL;
    }
}

function get_recipe_id($instructions, $recipe_name, $created_by, $author) {
    global $iUserConn;
    global $db;

    $sql = "SELECT recipie_id FROM $db.recipies WHERE instruction='$instructions' AND recipie_name='$recipe_name' AND created_by='$created_by' AND author='$author'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($id);
        $stmt->execute();
        while ($stmt->fetch()) {
            $id = $id;
        }
        $stmt->close();
    } else {
        echo 'Get recipie stmt didnt prepare.';
        exit;
    }

    if (!empty($id)) {
        return $id;
    } else {
        return 0;
    }
}

function get_recipe($recipe_id) {
    global $iUserConn;
    global $db;

    $sql = "SELECT * FROM $db.recipies WHERE recipie_id='$recipe_id'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($id, $name, $instruction, $created_by, $author, $date, $category, $sub_category);
        $stmt->execute();
        $array = array();
        while ($stmt->fetch()) {
            $array['id'] = $id;
            $array['name'] = $name;
            $array['instruction'] = $instruction;
            $array['created_by'] = $created_by;
            $array['author'] = $author;
            $array['date'] = $date;
            $array['category'] = $category;
            $array['sub_category'] = $sub_category;
        }
        $stmt->close();
    } else {
        echo 'stmt didnt prepare.';
        exit;
    }

    if (!empty($array)) {
        return $array;
    } else {
        return 0;
    }
}

function get_recipe_by_category($sub_category_id) {
    global $iUserConn;
    global $db;

    $sql = "SELECT recipie_id, recipie_name FROM $db.recipies WHERE sub_category=$sub_category_id";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($id, $name);
        $stmt->execute();
        $array = array();
        $final = array();
        while ($stmt->fetch()) {
            $array['id'] = $id;
            $array['name'] = $name;
            $final[] = $array;
        }
        $stmt->close();
    } else {
        echo 'recipe by id stmt didnt prepare.';
        exit;
    }

    if (!empty($final)) {
        return $final;
    } else {
        return 0;
    }
}

function get_all_recipies() {
    global $iUserConn;
    global $db;

    $sql = "SELECT * FROM $db.recipies";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($id, $name, $instruction, $created_by, $author, $date, $category, $sub_category);
        $stmt->execute();
        $array = array();
        $final_array = array();
        while ($stmt->fetch()) {
            $array['id'] = $id;
            $array['name'] = $name;
            $array['instruction'] = $instruction;
            $array['created_by'] = $created_by;
            $array['author'] = $author;
            $array['date'] = $date;
            $array['category'] = $category;
            $array['sub_category'] = $sub_category;
            $final_array[] = $array;
        }
        $stmt->close();
    } else {
        echo 'stmt didnt prepare.';
        exit;
    }

    if (!empty($final_array)) {
        return $final_array;
    } else {
        return 0;
    }
}

function print_recipe($recipe_id) {
    global $iUserConn;
    global $db;

    $psql = "SELECT recipie_name, author, date_submitted, instruction, created_by FROM $db.recipies WHERE recipie_id=$recipe_id";
    $pstmt = $iUserConn->prepare($psql);

    if ($pstmt) {
        $pstmt->bind_result($name, $author, $date, $instruction, $created_by);
        $pstmt->execute();
        while ($pstmt->fetch()) {
            $name = $name;
            $instruction = $instruction;
            $created_by = $created_by;
            $author = $author;
            $date = $date;
        }
        $pstmt->close();
    } else {
        echo __FUNCTION__ . '() stmt 1 didnt prepare.';
        exit;
    }

    $_SESSION['er_created_by'] = $created_by;

    $psql = "SELECT product_quantity, measurement_id, product_id FROM $db.ingredients WHERE recipie_id='$recipe_id'";
    $pstmt = $iUserConn->prepare($psql);

    if ($pstmt) {
        $pstmt->bind_result($product_quantity, $measurement_id, $product_id);
        $pstmt->execute();
        $i = array();
        $i_array = array();
        while ($pstmt->fetch()) {
            $i['quantity'] = $product_quantity;
            $i['measurement'] = $measurement_id;
            $i['prod'] = $product_id;
            $i_array[] = $i;
        }
        $pstmt->close();
    } else {
        echo __FUNCTION__ . '() stmt 2 didnt prepare.';
        exit;
    }

    $str = "";

    $str .= '<h2 id="recipe_name">' . $name . '</h2>
              <p id="author">Author: ' . $author . '</p>
              <p><b>Ingredients:</b></p>';

    foreach ($i_array as $i) {
        $measurement_id = get_measurement_symbol($i['measurement']);
        $product_id = get_product_name($i['prod']);

        $str .= '<p id="ingredient">' . $i['quantity'] . ' ' . $measurement_id['symbol'] . ' ' . $product_id . '</p>';
    }

    $date = date("F j, Y, g:i a", strtotime($date));

    $str .= '<p id="instruction"><b>Instructions: </b>' . $instruction . '</p>
              <p id="created_by">Recipe added by: ' . get_user_name($created_by) . '</p>
              <p id="date">Date added: ' . $date . '</p>';

    return $str;
}

// ----------------------------------------------------------------------------- INSERT RECIPE FUNCTIONS

function add_recipie($instructions, $recipie_name, $created_by, $author, $category, $sub_category) {
    global $iUserConn;
    global $db;

    $sql = "INSERT INTO $db.recipies (instruction, recipie_name, created_by, author, category, sub_category) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ssisii', $instructions, $recipie_name, $created_by, $author, $category, $sub_category);
        $stmt->execute();
        $rowschanged = $stmt->affected_rows;
        $stmt->close();
    } else {
        echo 'Add recipe statement did not prepare.';
        exit;
    }

    if ($rowschanged == 1) {
        return 1;
    } else {
        return 0;
    }
}

// ----------------------------------------------------------------------------- UPDATE RECIPE FUNCTIONS

function edit_recipie($recipie_name, $instructions, $created_by, $author, $recipie_id, $category, $sub_category) {
    global $iUserConn;
    global $db;

    $sql = "UPDATE $db.recipies SET recipie_name=?, instruction=?, created_by=?, author=?, category=?, sub_category=? WHERE recipie_id=?";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ssisi', $recipie_name, $instructions, $created_by, $author, $category, $sub_category, $recipie_id);
        $stmt->execute();
        $rowschanged = $stmt->affected_rows;
        $stmt->close();
    } else {
        echo 'Problem with Model.';
        exit;
    }

    if ($rowschanged == 1) {
        return 1;
    } else {
        return 0;
    }
}

/* * ***************************************************************************
 * *****************************************************************************
 * ************************* INGREDIENT FUNCTIONS ******************************
 * *****************************************************************************
 * ************************************************************************** */

// ----------------------------------------------------------------------------- SELECT INGREDIENT FUNCTIONS
function match_ingredients($product_quantity, $measurement_id, $product_id, $recipie_id) {
    global $iUserConn;
    global $db;

    $sql = "SELECT ingredients_id FROM $db.ingredients WHERE product_quantity = ? AND measurement_id = ? AND product_id = ? AND recipie_id = ?";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('iiii', $product_quantity, $measurement_id, $product_id, $recipie_id);
        $stmt->bind_result($id);
        $stmt->execute();
        while ($stmt->fetch()) {
            $id = $id;
        }
        $stmt->close();
    } else {
        echo 'Match ingredients stmt didnt prepare.';
        exit;
    }

    return $id;
}

function get_recipe_ingredients($recipe_id) {
    global $iUserConn;
    global $db;

    $sql = "SELECT * FROM $db.ingredients WHERE recipie_id='$recipe_id'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($id, $rec_id, $quantity, $measurement, $prod);
        $stmt->execute();
        $array = array();
        $final_array = array();
        while ($stmt->fetch()) {
            $array['id'] = $id;
            $array['recipie_id'] = $rec_id;
            $array['quantity'] = $quantity;
            $array['measurement'] = $measurement;
            $array['prod'] = $prod;
            $final_array[] = $array;
        }
        $stmt->close();
    } else {
        echo 'stmt didnt prepare.';
        exit;
    }

    if (!empty($final_array)) {
        return $final_array;
    } else {
        return 0;
    }
}

// ----------------------------------------------------------------------------- INSERT INGREDIENT FUNCTIONS

function add_ingredients($ingredients_array) {
    global $iUserConn;
    global $db;

    $sql = "INSERT INTO $db.ingredients (recipie_id, product_quantity, measurement_id, product_id) VALUES (?, ?, ?, ?)";

    foreach ($ingredients_array as $ing) {

        $id = $ing['id'];
        $quant = $ing['q'];
        $measurement = $ing['m'];
        $prod = $ing['p'];

        $stmt = $iUserConn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('iiii', $id, $quant, $measurement, $prod);
            $stmt->execute();
            $rowschanged = $stmt->affected_rows;
            $stmt->close();
        }
        if ($rowschanged != 1) {
            echo 'Insert did not work.';
            exit;
        }
    }

    return 1;
}

// ----------------------------------------------------------------------------- UPDATE INGREDIENT FUNCTIONS

function edit_ingredients($ingredients_array) {
    global $iUserConn;
    global $db;

    $sql = "UPDATE $db.ingredients SET product_quantity = ?, measurement_id = ?, product_id = ? WHERE ingredients_id = ?";

    foreach ($ingredients_array as $ing) {

        $id = $ing['id'];
        $quant = $ing['q'];
        $measurement = $ing['m'];
        $prod = $ing['p'];

        $stmt = $iUserConn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('iiii', $quant, $measurement, $prod, $id);
            $stmt->execute();
            $rowschanged = $stmt->affected_rows;
            $stmt->close();
        } else {
            echo 'Update on ingredients stmt did not work.';
            exit;
        }
    }

    return $rowschanged;
}

function get_recipe_with_ingredient($array) {
    global $iUserConn;
    global $db;

    $sql = "SELECT DISTINCT recipie_id, COUNT(recipie_id)
            FROM $db.ingredients INNER JOIN $db.products
            USING (product_id)
            WHERE product_name LIKE '%$array[0]%'";

    $size = count($array);
    for ($i = 1; $i < $size; $i++) {
        $sql .= " OR product_name LIKE '%$array[$i]%'";
    }

    $sql .= " GROUP BY recipie_id ORDER BY 2 DESC";

    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($id, $count);
        $stmt->execute();
        $array = array();
        $final = array();
        while ($stmt->fetch()) {
            $array['id'] = $id;
            $array['count'] = $count;
            $final[] = $array;
        }
        $stmt->close();
    } else {
        echo __FUNCTION__ . '() stmt didnt prepare.';
        exit;
    }

    $result = "";
    $result .= "<ul>";

    foreach ($final as $array) {
        if ($array['count'] == 1) {
            $result .= '<li class="drop_down" id="' . $array['id'] . '" onclick="get_recipe(\'' . $array['id'] . '\')" onmouseover="document.getElementById(this.id).setAttribute(\'style\',\'color:darkred;\');" onmouseout="document.getElementById(this.id).setAttribute(\'style\',\'color:black;\');" ><b>' . get_recipe_name($array['id']) . '</b> contains <span class="big_num">' . $array['count'] . '</span> ingredient from your pantry.</li>';
        } else {
            $result .= '<li class="drop_down" id="' . $array['id'] . '" onclick="get_recipe(\'' . $array['id'] . '\')" onmouseover="document.getElementById(this.id).setAttribute(\'style\',\'color:darkred;\');" onmouseout="document.getElementById(this.id).setAttribute(\'style\',\'color:black;\');" ><b>' . get_recipe_name($array['id']) . '</b> contains <span class="big_num">' . $array['count'] . '</span> ingredients from your pantry.</li>';
        }
    }

    $result .= "</ul>";

    if (!empty($result)) {
        return $result;
    } else {
        return 0;
    }
}

/* * ***************************************************************************
 * *****************************************************************************
 * ************************* CALENDAR FUNCTIONS ********************************
 * *****************************************************************************
 * ************************************************************************** */

// ----------------------------------------------------------------------------- SELECT CALENDAR FUNCTIONS

function get_client_calendar($client_id) {
    global $iUserConn;
    global $db;

    $sql = "SELECT * FROM $db.calendar WHERE client_id='$client_id'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($id, $year, $month, $day, $client_id);
        $stmt->execute();
        $array = array();
        $final_array = array();
        while ($stmt->fetch()) {
            $array['id'] = $id;
            $array['year'] = $year;
            $array['month'] = $month;
            $array['day'] = $day;
            $array['client_id'] = $client_id;
            $final_array[] = $array;
        }
        $stmt->close();
    } else {
        echo 'Get client calendar stmt didnt prepare.';
        exit;
    }

    if (!empty($final_array)) {
        return $final_array;
    } else {
        return 0;
    }
}

function check_client_calendar($client_id, $year, $month, $day) {
    global $iUserConn;
    global $db;

    $sql = "SELECT calendar_id FROM $db.calendar WHERE client_id='$client_id' AND year='$year' AND month='$month' AND day='$day'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($id);
        $stmt->execute();
        while ($stmt->fetch()) {
            $id = $id;
        }
        $stmt->close();
    } else {
        echo 'Check client calendar stmt didnt prepare.';
        exit;
    }

    if (!empty($id)) {
        return $id;
    } else {
        return NULL;
    }
}

// ----------------------------------------------------------------------------- INSERT CALENDAR FUNCTIONS

function add_calendar($year, $month, $day, $client_id) {
    global $iUserConn;
    global $db;

    $sql = "INSERT INTO $db.calendar (year, month, day, client_id) VALUES (?, ?, ?, ?)";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('isii', $year, $month, $day, $client_id);
        $stmt->execute();
        $rowschanged = $stmt->affected_rows;
        $stmt->close();
    }

    if ($rowschanged == 1) {
        return 1;
    } else {
        return 0;
    }
}

// ----------------------------------------------------------------------------- UPDATE CALENDAR FUNCTIONS

function edit_calendar($year, $month, $day, $client_id, $cal_id) {
    global $iUserConn;
    global $db;

    $sql = "UPDATE $db.calendar SET year=?, month=?, day=?, client_id=? WHERE calendar_id=?";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('isiii', $year, $month, $day, $client_id, $cal_id);
        $stmt->execute();
        $rowschanged = $stmt->affected_rows;
        $stmt->close();
    } else {
        echo 'Edit cal did not prepare.';
        exit;
    }

    if ($rowschanged == 1) {
        return 1;
    } else {
        return 0;
    }
}

/* * ***************************************************************************
 * *****************************************************************************
 * ************************* CLIENT FUNCTIONS **********************************
 * *****************************************************************************
 * ************************************************************************** */

// ----------------------------------------------------------------------------- SELECT CLIENT FUNCTIONS

function get_client($user_name, $password) {
    global $iUserConn;
    global $db;

    $sql = "SELECT client_id, client_first_name, client_last_name FROM $db.clients WHERE client_user_name='$user_name' AND client_password='$password'";

    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($id, $fname, $lname);
        $stmt->execute();
        $array = array();
        $final_array = array();
        while ($stmt->fetch()) {
            $array['id'] = $id;
            $array['fname'] = $fname;
            $array['lname'] = $lname;
            $final_array[] = $array;
        }
        $stmt->close();
    } else {
        echo 'stmt didnt prepare.';
        exit;
    }

    if (!empty($final_array)) {
        return $final_array;
    } else {
        return 0;
    }
}

function get_user_name($created_by) {
    global $iUserConn;
    global $db;

    $sql = "SELECT client_user_name FROM $db.clients WHERE client_id='$created_by'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($user_name);
        $stmt->execute();
        while ($stmt->fetch()) {
            $user_name = $user_name;
        }
        $stmt->close();
    } else {
        echo 'usr_name stmt didnt prepare.';
        exit;
    }

    if (!empty($user_name)) {
        return $user_name;
    } else {
        echo 'cannot obtain user_name';
        exit;
    }
}

// ----------------------------------------------------------------------------- INSERT CLIENT FUNCTIONS

function add_client($client_first, $client_last, $user_name, $password, $email) {
    global $iUserConn;
    global $db;

    $sql = "INSERT INTO $db.clients (client_first_name, client_last_name, client_user_name, client_password, client_email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('sssss', $client_first, $client_last, $user_name, $password, $email);
        $stmt->execute();
        $rowschanged = $stmt->affected_rows;
        $stmt->close();
    }

    if ($rowschanged == 1) {
        return 1;
    } else {
        return 0;
    }
}

// ----------------------------------------------------------------------------- UPDATE CLIENT FUNCTIONS

function edit_client($client_first, $client_last, $user_name, $password, $email, $client_id) {
    global $iUserConn;
    global $db;

    $sql = "UPDATE $db.clients SET client_first_name=?, client_last_name=?, client_user_name=?, client_password=?, client_email=? WHERE client_id=?";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('sssssi', $client_first, $client_last, $user_name, $password, $email, $client_id);
        $stmt->execute();
        $rowschanged = $stmt->affected_rows;
        $stmt->close();
    } else {
        echo 'Problem with Model.';
        exit;
    }

    if ($rowschanged == 1) {
        return 1;
    } else {
        return 0;
    }
}

/* * ***************************************************************************
 * *****************************************************************************
 * *********************** MEASUREMENT FUNCTIONS *******************************
 * *****************************************************************************
 * ************************************************************************** */

// ----------------------------------------------------------------------------- SELECT MEASUREMENT FUNCTIONS

function get_all_measurements() {
    global $iUserConn;
    global $db;

    $sql = "SELECT * FROM $db.measurements";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($id, $name, $symbol);
        $stmt->execute();
        $array = array();
        $final_array = array();
        while ($stmt->fetch()) {
            $array['id'] = $id;
            $array['name'] = $name;
            $array['symbol'] = $symbol;
            $final_array[] = $array;
        }
        $stmt->close();
    } else {
        echo 'stmt didnt prepare.';
        exit;
    }

    if (!empty($final_array)) {
        return $final_array;
    } else {
        return 0;
    }
}

function get_measurement_symbol($measurement_id) {
    global $iUserConn;
    global $db;

    $sql = "SELECT measurement_name, measurement_symbol FROM $db.measurements WHERE measurement_id='$measurement_id'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($name, $symbol);
        $stmt->execute();
        $m = array();
        while ($stmt->fetch()) {
            $m['name'] = $name;
            $m['symbol'] = $symbol;
        }
        $stmt->close();
    } else {
        echo 'usr_name stmt didnt prepare.';
        exit;
    }

    if (!empty($m)) {
        return $m;
    } else {
        echo 'cannot obtain measurement_id';
        exit;
    }
}

// ----------------------------------------------------------------------------- INSERT MEASUREMENT FUNCTIONS

function add_measurement($measurement_name, $measurement_symbol) {
    global $iUserConn;
    global $db;

    $sql = "INSERT INTO $db.measurements (measurement_name, measurement_symbol) VALUES (?, ?)";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ss', $measurement_name, $measurement_symbol);
        $stmt->execute();
        $rowschanged = $stmt->affected_rows;
        $stmt->close();
    }

    if ($rowschanged == 1) {
        return 1;
    } else {
        return 0;
    }
}

// ----------------------------------------------------------------------------- UPDATE MEASUREMENT FUNCTIONS

function edit_measurement($measurement_name, $measurement_symbol, $measurement_id) {
    global $iUserConn;
    global $db;

    $sql = "UPDATE $db.measurements SET measurement_name=?, measurement_symbol=? WHERE measurement_id=?";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ssi', $measurement_name, $measurement_symbol, $measurement_id);
        $stmt->execute();
        $rowschanged = $stmt->affected_rows;
        $stmt->close();
    } else {
        echo 'Problem with Model.';
        exit;
    }

    if ($rowschanged == 1) {
        return 1;
    } else {
        return 0;
    }
}

function check_measurement($measurement) {
    global $iUserConn;
    global $db;

    $sql = "SELECT measurement_id FROM $db.measurements WHERE measurement_name = '$measurement' OR measurement_symbol = '$measurement'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($measurement_id);
        $stmt->execute();
        $array = array();
        while ($stmt->fetch()) {
            $measurement_id = $measurement_id;
        }
        $stmt->close();
    } else {
        echo 'check product stmt didnt prepare.';
        exit;
    }


    if (empty($measurement_id)) {
        $result = add_measurement($measurement, $measurement);

        if ($result) {
            $sql = "SELECT measurement_id FROM $db.measurements WHERE measurement_name = '$measurement' OR measurement_symbol = '$measurement'";
            $stmt = $iUserConn->prepare($sql);

            if ($stmt) {
                $stmt->bind_result($measurement_id);
                $stmt->execute();
                $array = array();
                while ($stmt->fetch()) {
                    $measurement_id = $measurement_id;
                }
                $stmt->close();
            } else {
                echo 'check product after insert stmt didnt prepare.';
                exit;
            }
        } else {
            echo 'could not add product.';
            exit;
        }
    }

    return $measurement_id;
}

function measurement_suggestions($string) {
    global $iUserConn;
    global $db;

    $sql = "SELECT CONCAT(measurement_name,' - ',measurement_symbol)
            FROM $db.measurements
            WHERE measurement_name LIKE '$string%'
                OR measurement_symbol LIKE '$string%'";

    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($measurement);
        $stmt->execute();
        $array = array();
        while ($stmt->fetch()) {
            $array[] = $measurement;
        }
        $stmt->close();
    } else {
        echo __FUNCTION__ . '() stmt didnt prepare.';
        exit;
    }

    if (!empty($array)) {
        return $array;
    } else {
        return 0;
    }
}

/* * ***************************************************************************
 * *****************************************************************************
 * ************************* PRODUCT FUNCTIONS *********************************
 * *****************************************************************************
 * ************************************************************************** */

// ----------------------------------------------------------------------------- SELECT PRODUCT FUNCTIONS
function check_product($product) {
    global $iUserConn;
    global $db;

    $sql = "SELECT product_id FROM $db.products WHERE product_name = '$product'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($product_id);
        $stmt->execute();
        $array = array();
        while ($stmt->fetch()) {
            $product_id = $product_id;
        }
        $stmt->close();
    } else {
        echo 'check product stmt didnt prepare.';
        exit;
    }


    if (empty($product_id)) {
        $result = add_product($product);

        if ($result) {
            $sql = "SELECT product_id FROM $db.products WHERE product_name = '$product'";
            $stmt = $iUserConn->prepare($sql);

            if ($stmt) {
                $stmt->bind_result($product_id);
                $stmt->execute();
                $array = array();
                while ($stmt->fetch()) {
                    $product_id = $product_id;
                }
                $stmt->close();
            } else {
                echo 'check product after insert stmt didnt prepare.';
                exit;
            }
        } else {
            echo 'could not add product.';
            exit;
        }
    }

    return $product_id;
}

function product_suggestions($string) {
    global $iUserConn;
    global $db;

    $sql = "SELECT product_name FROM $db.products WHERE product_name LIKE '" . $string . "%'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($product_name);
        $stmt->execute();
        $array = array();
        while ($stmt->fetch()) {
            $array[] = $product_name;
        }
        $stmt->close();
    } else {
        echo 'stmt didnt prepare.';
        exit;
    }

    if (!empty($array)) {
        return $array;
    } else {
        return 0;
    }
}

function get_all_products() {
    global $iUserConn;
    global $db;

    $sql = "SELECT * FROM $db.products";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($product_id, $product_name);
        $stmt->execute();
        $array = array();
        $final_array = array();
        while ($stmt->fetch()) {
            $array['id'] = $product_id;
            $array['name'] = $product_name;
            $final_array[] = $array;
        }
        $stmt->close();
    } else {
        echo 'stmt didnt prepare.';
        exit;
    }

    if (!empty($final_array)) {
        return $final_array;
    } else {
        return 0;
    }
}

function get_product_name($product_id) {
    global $iUserConn;
    global $db;

    $sql = "SELECT product_name FROM $db.products WHERE product_id='$product_id'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($p);
        $stmt->execute();
        while ($stmt->fetch()) {
            $p = $p;
        }
        $stmt->close();
    } else {
        echo 'usr_name stmt didnt prepare.';
        exit;
    }

    if (!empty($p)) {
        return $p;
    } else {
        echo 'cannot obtain product_id';
        exit;
    }
}

// ----------------------------------------------------------------------------- INSERT PRODUCT FUNCTIONS

function add_product($prod_name) {
    global $iUserConn;
    global $db;

    $sql = "INSERT INTO $db.products (product_name) VALUES (?)";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('s', $prod_name);
        $stmt->execute();
        $rowschanged = $stmt->affected_rows;
        $stmt->close();
    }

    if ($rowschanged == 1) {
        return 1;
    } else {
        return 0;
    }
}

// ----------------------------------------------------------------------------- UPDATE PRODUCT FUNCTIONS

function edit_product($prod_name, $prod_id) {
    global $iUserConn;
    global $db;

    $sql = "UPDATE $db.products SET product_name=? WHERE product_id=?";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('si', $prod_name, $prod_id);
        $stmt->execute();
        $rowschanged = $stmt->affected_rows;
        $stmt->close();
    } else {
        echo 'Problem with Model.';
        exit;
    }

    if ($rowschanged == 1) {
        return 1;
    } else {
        return 0;
    }
}

/* * ***************************************************************************
 * *****************************************************************************
 * ***************************** MEALS FUNCTIONS *******************************
 * *****************************************************************************
 * ************************************************************************** */

// ----------------------------------------------------------------------------- SELECT MEALS FUNCTIONS

function get_meal_id($table_name, $calendar_id) {
    global $iUserConn;
    global $db;

    $sql = "SELECT " . $table_name . "_id FROM $db.$table_name WHERE calendar_id='$calendar_id'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($p);
        $stmt->execute();
        while ($stmt->fetch()) {
            $p = $p;
        }
        $stmt->close();
    } else {
        echo 'Get meal id stmt didnt prepare.';
        exit;
    }

    if (!empty($p)) {
        return $p;
    } else {
        return NULL;
    }
}

function get_meal_recipe_id($table_name, $calendar_id) {
    global $iUserConn;
    global $db;

    $sql = "SELECT recipe_id FROM $db.$table_name WHERE calendar_id='$calendar_id'";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($p);
        $stmt->execute();
        while ($stmt->fetch()) {
            $p = $p;
        }
        $stmt->close();
    } else {
        echo 'Get meal recipe id stmt didnt prepare.';
        exit;
    }

    if (!empty($p)) {
        return $p;
    } else {
        return NULL;
    }
}

// ----------------------------------------------------------------------------- INSERT MEALS FUNCTIONS

function add_meal($table_name, $calendar_id, $recipe_id) {
    global $iUserConn;
    global $db;

    $sql = "INSERT INTO $db.$table_name (calendar_id, recipe_id) VALUES (?,?)";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ii', $calendar_id, $recipe_id);
        $stmt->execute();
        $rowschanged = $stmt->affected_rows;
        $stmt->close();
    } else {
        echo 'Add meals stmt did not prepare.';
        exit;
    }

    if ($rowschanged == 1) {
        return 1;
    } else {
        return 0;
    }
}

// ----------------------------------------------------------------------------- UPDATE MEALS FUNCTIONS

function edit_meal($table_name, $calendar_id, $recipe_id) {
    global $iUserConn;
    global $db;

    $sql = "UPDATE $db.$table_name SET recipe_id=? WHERE calendar_id=?";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ii', $recipe_id, $calendar_id);
        $stmt->execute();
        $rowschanged = $stmt->affected_rows;
        $stmt->close();
    } else {
        echo 'Update meals stmt did not prepare.';
        exit;
    }

    if ($rowschanged == 1) {
        return 1;
    } else {
        return 0;
    }
}

/* * ***************************************************************************
 * *****************************************************************************
 * *************************** LUNCH FUNCTIONS *********************************
 * *****************************************************************************
 * ************************************************************************** */

/* * ***************************************************************************
 * *****************************************************************************
 * ************************** DINNER FUNCTIONS *********************************
 * *****************************************************************************
 * ************************************************************************** */


/* * ***************************************************************************
 * *****************************************************************************
 * ************************** SNACKS FUNCTIONS *********************************
 * *****************************************************************************
 * ************************************************************************** */

/* * ***************************************************************************
 * *****************************************************************************
 * ************************** CATEGORY FUNCTIONS *******************************
 * *****************************************************************************
 * ************************************************************************** */

function get_all_categories() {
    global $iUserConn;
    global $db;

    $sql = "SELECT * FROM $db.categories";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($c_id, $c);
        $stmt->execute();
        $array = array();
        $final = array();
        while ($stmt->fetch()) {
            $array['id'] = $c_id;
            $array['category'] = $c;
            $final[] = $array;
        }
        $stmt->close();
    } else {
        echo 'Get all category stmt didnt prepare.';
        exit;
    }

    if (!empty($final)) {
        return $final;
    } else {
        return NULL;
    }
}

function get_all_sub_categories($category_id) {
    global $iUserConn;
    global $db;

    $sql = "SELECT * FROM $db.sub_categories WHERE category_id = $category_id";
    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($sc_id, $c_id, $sc);
        $stmt->execute();
        $array = array();
        $final = array();
        while ($stmt->fetch()) {
            $array['id'] = $sc_id;
            $array['category_id'] = $c_id;
            $array['sub_category'] = $sc;
            $final[] = $array;
        }
        $stmt->close();
    } else {
        echo 'Get all sub_category stmt didnt prepare.';
        exit;
    }

    if (!empty($final)) {
        return $final;
    } else {
        return NULL;
    }
}

function print_categories() {
    global $iUserConn;
    global $db;

    $recipe = array();
    $sc_array = array();
    $final = array();
    $final_array = array();

    $c_array = array();
    $c_array = get_all_categories();

    foreach ($c_array as $c) {
        $final['id'] = $c['id'];
        $final['category'] = $c['category'];
        $sc_array = get_all_sub_categories($c['id']);
        $final['sub_category'] = $sc_array;
        $final_array[] = $final;
    }

    return $final_array;
}

/* * ***************************************************************************
 * *****************************************************************************
 * ******************** SHOPPING LIST FUNCTIONS ********************************
 * *****************************************************************************
 * ************************************************************************** */

function get_monthly_shoppinglist($year, $month, $client_id) {
    global $iUserConn;
    global $db;

    $sql = "SELECT SUM($db.ingredients.product_quantity), $db.measurements.measurement_name, $db.products.product_name
            FROM $db.ingredients JOIN $db.measurements USING(measurement_id)
                JOIN $db.products USING(product_id)
                JOIN ((SELECT * FROM breakfast) UNION ALL (SELECT * FROM lunch) UNION ALL (SELECT * FROM dinner) UNION ALL (SELECT * FROM snacks)) fab
                     ON $db.ingredients.recipie_id = fab.recipe_id
                JOIN $db.calendar USING(calendar_id)
            WHERE $db.calendar.year = $year
            AND $db.calendar.month = '$month'
            AND $db.calendar.client_id = $client_id
            GROUP BY $db.measurements.measurement_name, $db.products.product_name";

    $stmt = $iUserConn->prepare($sql);

    if ($stmt) {
        $stmt->bind_result($quantity, $measurement_name, $product_name);
        $stmt->execute();
        $limit_array = array();
        $array = array();
        $final = array();
        while ($stmt->fetch()) {
            $array['quantity'] = $quantity;
            $array['measurement_name'] = $measurement_name;
            $array['product_name'] = $product_name;
            $final[] = $array;
        }
        $stmt->close();
    } else {
        echo 'Shopping list stmt didnt prepare.';
        exit;
    }


    if (!empty($final)) {
        return $final;
    } else {
        return NULL;
    }
}

/*
  SELECT SUM(ingredients.product_quantity), measurements.measurement_name, products.product_name
  FROM ingredients JOIN measurements USING(measurement_id)
  JOIN products USING(product_id)
  JOIN ((SELECT * FROM breakfast) UNION ALL (SELECT * FROM lunch) UNION ALL (SELECT * FROM dinner) UNION ALL (SELECT * FROM snacks)) fab
  ON ingredients.recipie_id = fab.recipe_id
  JOIN calendar USING(calendar_id)
  WHERE calendar.year BETWEEN 2012 AND 2013
  AND calendar.month BETWEEN 'January' AND 'February'
  AND calendar.month BETWEEN '1' AND '31'
  AND calendar.client_id = $client_id
  GROUP BY measurements.measurement_name, products.product_name





 */
?>