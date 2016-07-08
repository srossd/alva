<?php
mail($_POST["email"],$_POST["subject"],$_POST["body"],$_POST["headers"]);
?>