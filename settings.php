<?php
/**
 * PHP file that contains the settings for the database connection
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file settings.php
 */

// Check if the file executing the script is the same as the file itself
if (basename($_SERVER["PHP_SELF"]) == basename(__FILE__)) {
    header("location: index.php");
    exit();
}


// Settings for the database connection
$host = "enter host here";
$user = "enter user here";
$pwd = "enter password here";
$sql_db = "enter database name here";
?>