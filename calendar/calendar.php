<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/model.php';

if (!empty($_SESSION['cal_month'])) {
    $month = $_SESSION['cal_month'];
}
if (!empty($_SESSION['cal_year'])) {
    $year = $_SESSION['cal_year'];
}
$client_id = $_SESSION['client_id'];
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/nav.php'; ?>
<div id="content">
        <div id="pick_date">
            <div class="title">Please pick a Month and Year</div>
            <form method="post" action="/">
                <label>Month: </label>
                <select name="month">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <label>Year: </label>
                <select name="year">
                    <option value="2012">2012</option>
                    <option value="2013">2013</option>
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                </select>
                <input type="submit" name="action" value="Generate Calendar" />
            </form>
        </div>

        <?php
        if ($month && $year) {

            switch ($month) {
                case 1:
                    $month = "January";
                    $number_of_days = 31;
                    break;
                case 2:
                    $month = "February";
                    $time = '1 February ' . $year;
                    if (date('L', strtotime($time))) {
                        $number_of_days = 29;
                    } else {
                        $number_of_days = 28;
                    }
                    break;
                case 3:
                    $month = "March";
                    $number_of_days = 31;
                    break;
                case 4:
                    $month = "April";
                    $number_of_days = 30;
                    break;
                case 5:
                    $month = "May";
                    $number_of_days = 31;
                    break;
                case 6:
                    $month = "June";
                    $number_of_days = 30;
                    break;
                case 7:
                    $month = "July";
                    $number_of_days = 31;
                    break;
                case 8:
                    $month = "August";
                    $number_of_days = 31;
                    break;
                case 9:
                    $month = "September";
                    $number_of_days = 30;
                    break;
                case 10:
                    $month = "October";
                    $number_of_days = 31;
                    break;
                case 11:
                    $month = "November";
                    $number_of_days = 30;
                    break;
                case 12:
                    $month = "December";
                    $number_of_days = 31;
                    break;
            }

            echo '<div id="calendar">
          <div class="title">' . $month . ' ' . $year . '</div>
          <table border="1">
          <tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>';

            $row = 0;
            echo '<tr>';

            for ($k = 1; $k <= $number_of_days; $k++) {

                // Day number
                $day = $k;

                $calendar_id = check_client_calendar($client_id, $year, $month, $day);

                if ($calendar_id != NULL) {

                    $bfast_id = get_meal_recipe_id('breakfast', $calendar_id);
                    $bfast = get_recipe_name($bfast_id);

                    $lunch_id = get_meal_recipe_id('lunch', $calendar_id);
                    $lunch = get_recipe_name($lunch_id);

                    $dinner_id = get_meal_recipe_id('dinner', $calendar_id);
                    $dinner = get_recipe_name($dinner_id);

                    $snacks_id = get_meal_recipe_id('snacks', $calendar_id);
                    $snacks = get_recipe_name($snacks_id);
                } else {
                    $bfast = NULL;
                    $lunch = NULL;
                    $dinner = NULL;
                    $snacks = NULL;
                }

                // Cell number
                $time = $k . ' ' . $month . ' ' . $year;
                $cell_num = date('w', strtotime($time));

                // if it is the first day put the day in the proper cell.
                if ($day == 1) {
                    switch ($cell_num) {
                        case 6:
                            echo '<td><span class="date">&nbsp;</span></td>';
                        case 5:
                            echo '<td><span class="date">&nbsp;</span></td>';
                        case 4:
                            echo '<td><span class="date">&nbsp;</span></td>';
                        case 3:
                            echo '<td><span class="date">&nbsp;</span></td>';
                        case 2:
                            echo '<td><span class="date">&nbsp;</span></td>';
                        case 1:
                            echo '<td><span class="date">&nbsp;</span></td>';
                        case 0:
                            echo '<td><span class="date"><a href="/?action=meals&day=' . $day . '&month=' . $month . '&year=' . $year . '" >' . $day . '</a></span><p><b>Breakfast: </b>' . $bfast . '</p><p><b>Lunch: </b>' . $lunch . '</p><p><b>Dinner: </b>' . $dinner . '</p><p><b>Snack: </b>' . $snacks . '</p></td>';
                    }
                } else {
                    echo '<td><span class="date"><a href="/?action=meals&day=' . $day . '&month=' . $month . '&year=' . $year . '" >' . $day . '</a></span><p><b>Breakfast: </b>' . $bfast . '</p><p><b>Lunch: </b>' . $lunch . '</p><p><b>Dinner: </b>' . $dinner . '</p><p><b>Snack: </b>' . $snacks . '</p></td>';
                }

                if ($cell_num == 6) {
                    echo '</tr><tr>';
                    $row++;
                }
            }

            echo'</table></div>';
        }
        
        ?>
    </div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/footer.php'; ?>