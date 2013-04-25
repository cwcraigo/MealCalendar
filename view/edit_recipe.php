<?php
session_start();

$id = $_SESSION['ed_rec_id'];
$name = $_SESSION['er_name'];
$inst = $_SESSION['er_instructions'];
$author = $_SESSION['er_author'];
$i_array = array();
$i_array = $_SESSION['ed_ing_array'];

if (!empty($_SESSION['measure_array']) || !empty($_SESSION['prod_array'])) {
    $measure_array = $_SESSION['measure_array'];
    $prod_array = $_SESSION['prod_array'];
}

if (!empty($_SESSION['r_message'])) {
    $r_message = $_SESSION['r_message'];
}
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/nav.php'; ?>

<div id="content">
    <div class="title">Edit Recipe</div>

    <?php
    if (!empty($r_message)) {
        echo "<p>$r_message</p>";
        unset($_SESSION['r_message']);
    }
    ?>

    <form method="post" action="/">
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <label for="recipie_name" >Recipe Name: </label>
        <input type="text" id="recipie_name" name="recipie_name" size="30" value="<?php echo $name; ?>" required />
        <br />
        <label for="author" >Author: </label>
        <input type="text" id="author" name="author" value="<?php echo $author; ?>" size="30" required />
        <br />

        <?php
        $i = 0;
        if (!empty($i_array)) {
            foreach ($i_array as $m) {

                $i++;
                echo '<input type="hidden" name="ing_id' . $i . '" value="' . $m['ing_id'] . '" />
                    <input type="text" id="quantity" name="quantity' . $i . '" value="' . $m['quantity'] . '" size="10" />
                    <select name="measurement' . $i . '">
                    <option value="' . $m['measurement_id'] . '" selected >' . $m['symbol'] . ' = ' . $m['measurement_name'] . '</option>';

                foreach ($measure_array as $ma) {
                    echo '<option value="' . $ma['id'] . '">' . $ma['symbol'] . ' = ' . $ma['name'] . '</option>';
                }

                echo '</select>
                    <select name="product' . $i . '">
                    <option value="' . $m['product_id'] . '" selected >' . $m['product'] . '</option>';

                foreach ($prod_array as $p) {
                    echo '<option value="' . $p['id'] . '">' . $p['name'] . '</option>';
                }

                echo '</select><br />';
            }
        }
        ?>

        <input type="hidden" name="ing_num" value="<?php echo $i; ?>" />
        <textarea id="instructions" name="instructions" cols="60" rows="10" required><?php echo $inst; ?></textarea>
        <br />
        <input type="submit" name="action" value="Edit Recipe" />

    </form>

</div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/footer.php'; ?>
