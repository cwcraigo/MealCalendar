<?php
session_start();
if ($_SESSION['inventory_result']) {
    $result = $_SESSION['inventory_result'];
    unset($_SESSION['inventory_result']);
}
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/nav.php'; ?>
<div id="content">

    <div class="title">Ingredients on Hand</div>

    <p>Please enter your inventory into the field(s) below to obtain a list of recipes that contain the ingredient(s) from your inventory.</p>

    <div id="left_section" >

        <form method="POST" action="/" >
            <label><b>Ingredients:</b></label>
            <div class="inputs">
                <div><input type="text" name="dynamic0" class="field" /></div>
            </div>
            
            <input id="field_num" name="field_num" type="hidden" value="1" />
            
            <a href="#" id="add">Add Ingredient</a> | <a href="#" id="remove">Remove Last Ingredient</a>
            <br />
            <input name="action" type="submit" class="submit" value="Search Recipes" />

        </form>
        <!--        <a href="#" id="add">Add Ingredient</a> | <a href="#" id="remove">Remove Last Ingredient</a>  | <a href="#" id="reset">Reset Ingredients</a>  -->

    </div>

    <div id="right_section">
        <?php echo $result ?>
    </div>
    <div id="lower_section">
        <div class="hidden" id="popup" >SORRY! COULD NOT DISPLAY RECIPE.</div>
    </div>
</div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/footer.php'; ?>