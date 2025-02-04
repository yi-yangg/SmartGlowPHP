<?php
/**
 * PHP file that returns the product options based on the product name
 * 
 * This file is used by Javascript to get the product options based on the product name
 * It is used when the user clicks the add product button in the enquiry page, which will trigger an AJAX request to this file
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file getProductOptions.php
 */

// If not coming from enquire.php, redirect to index.php
if (!isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "enquire.php") == false) {
    header("location: index.php");
    exit();
}

require_once("utilities.php");

try {
    // Check if product name is set
    if (!isset($_GET['product_name'])) {
        throw new Exception("Product name is required.");
    }

    // Get the product name from the GET request
    $productName = sanitise_input($_GET['product_name']);

    // Get the product options based on the product ID
    $productOptionDAO = new ProductOptionDAO();
    $options = $productOptionDAO->getProductOptions($productName);

    // Return the options as JSON
    header('Content-Type: application/json');
    echo json_encode($options);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}

?>