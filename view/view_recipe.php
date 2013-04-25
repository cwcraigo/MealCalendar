<?php
session_start();
if (!empty($_SESSION['recipe'])) {
    $recipe = $_SESSION['recipe'];
}
if (!empty($_SESSION['error_recipe'])) {
    $error = $_SESSION['error_recipe'];
}
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/nav.php'; ?>
<div id="content">

    <div class="title">Recipe</div>

    <?php
    if (!empty($error)) {
        echo $error;
    }
    if (!empty($recipe)) {
        echo $recipe;
    }
    ?>

</div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/footer.php'; ?>
