<?php
/**
 * PHP file that deletes an order from the MySQL database and redirects back to manager.php
 * Only accessible via POST request from manager.php
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file delete_order.php
 */


// If not coming from manager.php, redirect to index.php
if (!isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "manager.php") == false) {
    header("location: index.php");
    exit();
}

// If not coming from a POST request, redirect to index.php
if (!isset($_POST["order-id"])) {
    header("location: index.php");
    exit();
}

// Get input data from POST request
require_once("utilities.php");
$orderNo = $_POST["order-id"];

// Delete order from MySQL database using DAO
$orderDAO = new OrderDAO();
$orderDAO->deleteOrder($orderNo);

// Redirect back to manager.php
header("location: manager.php");
?>