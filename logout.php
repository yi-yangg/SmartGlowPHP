<?php
/**
 * PHP file that logs out the user and redirects back to login.php
 * Unsets the user_id and user_type session variables and destroys the session
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file logout.php
 */

session_start();
unset($_SESSION["user_id"]);
unset($_SESSION["user_type"]);
session_destroy();

header("location: login.php");

?>