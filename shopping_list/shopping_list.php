<?php
session_start();

if (!empty($_SESSION['shopping_list'])) {
    $shopping_list = array();
    $shopping_list = $_SESSION['shopping_list'];
}
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/nav.php'; ?>
<div id="content">
    <div class="title">Shopping List</div>

    <p>Please select the date you want to produce a shopping list for.</p>

        <form method="post" action="/">
            
            <label>Year: </label>
            <select name="year" onchange="" >
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
            <label>Month: </label>
            <select name="month">
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
            </select>

            <br />

            <input type="submit" name="action" value="Generate Shopping List" />

        </form>

    <?php
    if ($shopping_list) {
        foreach ($shopping_list as $list) {
            echo '<p>' . $list['quantity'] . ' ' . $list['measurement_name'] . ' ' . $list['product_name'] . '</p>';
        }
    }
    ?>


</div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/footer.php'; ?>



