<?php
session_start();

if (!empty($_SESSION['recipe_array']) || !empty($_SESSION['date'])) {
    $date = $_SESSION['date'];
    $day = $date['day'];
    $month = $date['month'];
    $year = $date['year'];
    $recipe_array = $_SESSION['recipe_array'];
    $title = 'Meals for ' . $month . ' ' . $day . ', ' . $year;
    $_SESSION['meals_title'] = $title;
}
if (!empty($_SESSION['message'])) {
    $message = $_SESSION['message'];
}

if (!empty($_SESSION['meals'])) {
    $meals = $_SESSION['meals'];
}
if (!empty($_SESSION['r_message'])) {
    $r_message = $_SESSION['r_message'];
}
if (!empty($_SESSION['c_array'])) {
    $c_array = $_SESSION['c_array'];
}
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/nav.php'; ?>

<div id="content">
    <div class="title"><?php echo $_SESSION['meals_title']; ?></div>
    <div class="errors">
        <?php
        if ($message) {
            foreach ($message as $m) {
                echo "<p>$m</p>";
            }
            unset($_SESSION['message']);
        }
        if ($r_message) {
            echo "<p>$r_message</p>";
            unset($_SESSION['r_message']);
        }
        ?>
    </div>

    <div id="left_section" >
        <div class="meals" id="meals">

            <form method="POST" action="/" >
                <label><b>Assign recipes to specific meals of the day.</b></label>
                <br /><br />
                <label>Breakfast:&#160;&#160;</label>
                <select name="bfast" id="bfast" >
                    <?php
                    if ($meals['bfast_id'] != NULL) {
                        echo '<option value="' . $meals['bfast_id'] . '" selected >' . $meals['bfast_name'] . '</option>';
                    } else {
                        echo '<option value="" selected >Please Select a Recipe</option>';
                    }

                    foreach ($recipe_array as $r) {
                        echo '<option value="' . $r['id'] . '">' . $r['name'] . '</option>';
                    }
                    ?>
                </select>
                <input type="button" value="View Recipe" onclick="get_recipe(bfast.value)"/>

                <br />

                <label>Lunch:&#160;&#160;&#160;&#160;&#160;&#160;&#160;</label>
                <select name="lunch" id="lunch" >
                    <?php
                    if ($meals['lunch_id'] != NULL) {
                        echo '<option value="' . $meals['lunch_id'] . '" selected >' . $meals['lunch_name'] . '</option>';
                    } else {
                        echo '<option value="" selected >Please Select a Recipe</option>';
                    }

                    foreach ($recipe_array as $r) {
                        echo '<option value="' . $r['id'] . '">' . $r['name'] . '</option>';
                    }
                    ?>
                </select>
                <input type="button" value="View Recipe" onclick="get_recipe(lunch.value)" />

                <br />

                <label>Dinner:&#160;&#160;&#160;&#160;&#160;&#160;</label>
                <select name="dinner" id="dinner" >
                    <?php
                    if ($meals['dinner_id'] != NULL) {
                        echo '<option value="' . $meals['dinner_id'] . '" selected >' . $meals['dinner_name'] . '</option>';
                    } else {
                        echo '<option value="" selected >Please Select a Recipe</option>';
                    }

                    foreach ($recipe_array as $r) {
                        echo '<option value="' . $r['id'] . '">' . $r['name'] . '</option>';
                    }
                    ?>
                </select>
                <input type="button" value="View Recipe" onclick="get_recipe(dinner.value)" />

                <br />

                <label>Snack:&#160;&#160;&#160;&#160;&#160;&#160;&#160;</label>
                <select name="snack" id="snack">
                    <?php
                    if ($meals['snacks_id'] != NULL) {
                        echo '<option value="' . $meals['snacks_id'] . '" selected >' . $meals['snacks_name'] . '</option>';
                    } else {
                        echo '<option value="" selected >Please Select a Recipe</option>';
                    }

                    foreach ($recipe_array as $r) {
                        echo '<option value="' . $r['id'] . '">' . $r['name'] . '</option>';
                    }
                    ?>
                </select>
                <input type="button" value="View Recipe" onclick="get_recipe(snack.value)" />

                <br />

                <input type="submit" name="action" value="Apply Meals" />

            </form>
        </div>
        <br />
        <div id="category">
            <label><b>View recipes by Categories and Sub-Categories.</b></label>
            <br />
            <ul>
                <?php
                foreach ($c_array as $c) {
                    echo '<li class="drop_down1" id="' . $c['category'] . '1" onclick="SubCategory(\'' . $c['category'] . '\')" onmouseover="document.getElementById(this.id).setAttribute(\'style\',\'color:darkred;\');" onmouseout="document.getElementById(this.id).setAttribute(\'style\',\'color:black;\');" >' . $c['category'] . '</li>
                    <ul id="' . $c['category'] . '"  class="hidden" >';
                    foreach ($c['sub_category'] as $sc) {
                        echo '<li class="drop_down2" id="' . $sc['sub_category'] . '1" onclick="SubCategory(\'' . $sc['sub_category'] . '\')" onmouseover="document.getElementById(this.id).setAttribute(\'style\',\'color:darkblue;\');" onmouseout="document.getElementById(this.id).setAttribute(\'style\',\'color:black;\');" >' . $sc['sub_category'] . '</li>
                        <ul id="' . $sc['sub_category'] . '"  class="hidden" >';
                        foreach ($recipe_array as $recipe) {
                            if ($recipe['sub_category'] == $sc['id']) {
                                echo '<li class="drop_down" id="' . $recipe['name'] . '" value="' . $recipe['id'] . '" onclick="get_recipe(this.value)" onmouseover="document.getElementById(this.id).setAttribute(\'style\',\'color:goldenrod;\');" onmouseout="document.getElementById(this.id).setAttribute(\'style\',\'color:black;\');" >' . $recipe['name'] . '</li>';
                            }
                        }
                        echo '</ul>';
                    }
                    echo '</ul>';
                }
                ?>
            </ul>
        </div> 
    </div>

    <div id="right_section">
        <div class="hidden" id="popup" >SORRY! COULD NOT DISPLAY RECIPE.</div>
    </div>


</div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/footer.php'; ?>