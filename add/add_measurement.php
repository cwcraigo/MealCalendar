<?php
session_start();
if (!empty($_SESSION['m_message'])) {
    $m_message = $_SESSION['m_message'];
}
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/nav.php'; ?>
        <div id="content">
            <div class="title">Add Measurement</div>

            <?php
            if (!empty($m_message)) {
                echo "<p>$m_message</p>";
                unset ($_SESSION['m_message']);
            }
            ?>
            <form method="post" action="/">
                <input type="text" id="measurement_name" name="measurement_name" placeholder="Measurement Name" size="15" />
                <input type="text" id="measurement_symbol" name="measurement_symbol" placeholder="Measurement Symbol" size="15" />
                <input type="submit" name="action" value="Add Measurement" />
            </form>
        </div>
   <?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/footer.php'; ?>