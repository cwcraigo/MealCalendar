<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/model.php';

if (!empty($_GET['category'])) {
    $category_id = $_GET['category'];
    $sub_category_array = array();
    $sub_category_array = get_all_sub_categories($category_id);
}

if (!empty($_GET['ing_num'])) {
    $_SESSION['ing_num'] = $_GET['ing_num'];
}

$ing_num = $_SESSION['ing_num'];

if (!empty($_SESSION['measure_array']) || !empty($_SESSION['prod_array']) || !empty($_SESSION['c_array'])) {
    $measure_array = $_SESSION['measure_array'];
    $prod_array = $_SESSION['prod_array'];
    $c_array = $_SESSION['c_array'];
}
if (!empty($_SESSION['r_message'])) {
    $r_message = $_SESSION['r_message'];
}
if (!empty($_SESSION['p_message'])) {
    $p_message = $_SESSION['p_message'];
}
if (!empty($_SESSION['m_message'])) {
    $m_message = $_SESSION['m_message'];
}
if (!empty($_SESSION['category_array'])) {
    $category_array = $_SESSION['category_array'];
}
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/nav.php'; ?>
<div id="content">
    <div id="p_suggest" class="hidden"><p><span id="product_suggestions"></span></p></div>
    <div class="title">Add Recipe</div>

    <?php
    if (!empty($r_message)) {
        echo "<p>$r_message</p>";
        unset($_SESSION['r_message']);
    }

    if (!empty($p_message)) {
        echo "<p>$p_message</p>";
        unset($_SESSION['p_message']);
    }
    ?>

    <?php if (empty($category_id)) : ?>

        <form method="GET" action="">
            <select name="category" onchange="this.form.submit()" >
                <option value="" selected >Select Recipe Category</option>
                <?php
                foreach ($category_array as $c) {
                    echo '<option value="' . $c['id'] . '" >' . $c['category'] . '</option>';
                }
                ?>
            </select>
        </form>

    <?php endif; ?>

    <?php if (!empty($category_id)) : ?>

        <form method="post" action="/">
            <p><b>Category:</b> <?php echo $category_array[$category_id - 1]['category'] ?></p>
            <input type="hidden" name="category_num" value="<?php echo $sub_category_array[0]['category_id'] ?>" />
            <select name="sub_category" >
                <option value="" selected >Select Recipe Sub_Category</option>
                <?php
                foreach ($sub_category_array as $sc) {
                    echo '<option value="' . $sc['id'] . '" >' . $sc['sub_category'] . '</option>';
                }
                ?>
            </select>
            <br />
            <label for="recipie_name" >Recipe Name: </label>
            <input type="text" id="recipie_name" name="recipie_name" size="30" required />
            <br />
            <label for="author" >Author: </label>
            <input type="text" id="author" name="author" placeholder="ex: Grandma\'s, Mom\'s, Your name, etc" size="30" required />
            <br />

            <!--dynamic input for ingredients-->
            <div class="inputs">
                <div class="field" >
                    <input type="text" id="quantity1" name="quantity1" placeholder="Quantity" size="10" /> | 
                    <input type="text" id="measurement1" name="measurement1" placeholder="Measurment Name or Symbol" size="15" onkeyup="measurements(this.value)" /> | 
                    <input type="text" id="product1" name="product1" placeholder="Product Name" size="15" onkeyup="products(this.value)" />
                </div>
            </div>

            <input id="field_num" name="field_num" type="hidden" value="1" />
            <a href="#" id="add_add">Add Ingredient</a> | <a href="#" id="remove_add">Remove Last Ingredient</a>
            <br />
            <textarea id="instructions" name="instructions" placeholder="Instructions" cols="60" rows="10" required></textarea>
            <br />
            <input type="submit" name="action" value="Add Recipe" />

        </form>
    <?php endif; ?>
</div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/footer.php'; ?>
