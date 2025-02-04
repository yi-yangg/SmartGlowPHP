<?php
/**
 * PHP file that cancels an order and redirects back to index.php
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file cancel_order.php
 */

session_start();
// Check if the file executing the script is the same as the file itself
if (!isset($_SESSION["orderNo"])) {
    header("location: index.php");
    exit();
}

require_once("utilities.php");

$orderNo = $_SESSION["orderNo"];
$orderDAO = new OrderDAO();
// Delete order from MySQL database
$orderDAO->deleteOrder($orderNo);

$userID = "";
$userType = "";

if (isset($_SESSION["user_id"])) {
    $userID = $_SESSION["user_id"];
    $userType = $_SESSION["user_type"];
}

// Remove all session variables
session_unset();
if (!empty($userID)) {
    $_SESSION["user_id"] = $userID;
    $_SESSION["user_type"] = $userType;
}

session_destroy();

// Redirect back to index.php
header("location: index.php");


