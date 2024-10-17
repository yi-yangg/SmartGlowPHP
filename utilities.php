<?php

// Check if the file executing the script is the same as the file itself
// If users access utilities.php directly, it will redirect them to index.php
if (basename($_SERVER["PHP_SELF"]) == basename(__FILE__)) {
    header("location: index.php");
    exit();
}

// Function definitions here
function sanitise_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>