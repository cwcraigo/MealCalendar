<?php
session_start();

if ($_SESSION['errors']) {
    $errors = array();
    $errors = $_SESSION['errors'];
    $_SESSION['errors'] = 0;
}

if ($_SESSION['reg_array']) {
    $reg_array = array();
    $reg_array = $_SESSION['reg_array'];
    $_SESSION['reg_array'] = 0;
}
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/nav.php'; ?>
<div id="content">
    <div class="title">Register</div>
    <p>Please enter data into all fields.</p>

    <?php
    if ($errors) {
        echo '<ul class="nolist noticeme">';
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo '</ul>';
    }
    ?>

    <form method="post" action="/">
        <label for="cfirst">First Name: </label>
        <input type="text" id="cfirst" name="cfirst" size="20" value="<?php echo "$reg_array[0]"; ?>" required />
        <br />
        <label for="clast">Last Name: </label>
        <input type="text" id="clast" name="clast" size="20" value="<?php echo "$reg_array[1]"; ?>" required />
        <br />
        <label for="cemail">Email: </label>
        <input type="email" id="cemail" name="cemail" size="20" value="<?php echo "$reg_array[2]"; ?>" required />
        <br />
        <label for="user_name">User Name: </label>
        <input type="text" id="user_name" name="user_name" size="20" value="<?php echo "$reg_array[3]"; ?>" required />
        <br />
        <label for="cpassword">Password: </label>
        <input type="password" id="cpassword" name="cpassword" size="20" required />
        <br />
        <label for="cpassword2">Re-type Password: </label>
        <input type="password" id="cpassword2" name="cpassword2" size="20" required />
        <br />
        <input type="submit" name="action" value="Register" />
    </form>
</div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/footer.php'; ?>