<?php
/**
 * PHP file that updates the status of an order and redirects back to manager.php
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file update_status.php
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
$orderNo = sanitise_input($_POST["order-id"]);
$newStatus = sanitise_input($_POST["order-status"]);

// Update order status in MySQL database using DAO
$orderDAO = new OrderDAO();
$orderDAO->updateOrderStatus($orderNo, $newStatus);

// Redirect back to manager.php
header("location: manager.php");
?>