<?php
session_start();
if (!empty($_SESSION['p_message'])) {
    $p_message = $_SESSION['p_message'];
}
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/nav.php'; ?>
        <div id="content">
            <div class="title">Add Product</div>
            
            <?php
            if (!empty($p_message)) {
                echo "<p>$p_message</p>";
                unset ($_SESSION['p_message']);
            }
            ?>
            <form method="post" action="/">
                <input type="text" id="product_name" name="product_name" placeholder="Product Name" size="15" />
                <input type="submit" name="action" value="Add Product" />
            </form>
        </div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/modules/footer.php'; ?>