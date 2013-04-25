<?php 
session_start();

if($_SESSION['client_id'] == $_SESSION['er_created_by']) : ?>
    <input type="button" onclick="parent.location = '/index.php?action=Edit&amp;id=<?php echo $q; ?>'" value="Edit" /> 
<?php endif; ?>


<input type="button" onclick="document.getElementById('popup').setAttribute('class','hidden')" value="Close" />
