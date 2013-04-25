<?php
session_start();

if ($_SESSION['login_error']) {
    $login_error = $_SESSION['login_error'];
}
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/nav.php'; ?>
        <div id="content">
            <div class="title">Login</div>
            <p>Please enter data into all fields.</p>
            <?php
            if ($login_error) {
                echo '<ul class="nolist noticeme">';
                foreach ($login_error as $error) {
                    echo "<li>$error</li>";
                }
                echo '</ul>';
            }
            ?>
            <form method="post" action="/">
                    <label for="cusername" >Username: </label>
                    <input type="text" id="cusername" placeholder="Username" name="cusername" size="20" required />
                    <br />
                    <label for="cpassword" >Password: </label>
                    <input type="password" id="cpassword" name="cpassword" size="20" required />
                    <br />
                    <input type="submit" name="action" value="Login" />
                    <br />
            </form>
        </div>
  <?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/footer.php'; ?>