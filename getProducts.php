<?php
/**
 * PHP file that retrieves all products from the MySQL database and returns them as a JSON object
 * 
 * This file is used by Javascript which triggers an AJAX request to this file to get all products
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file getProducts.php
 */

if (!isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "enquire.php") == false) {
    header("location: index.php");
    exit();
}


require_once("utilities.php");

try {
    $productDAO = new ProductDAO();
    $products = $productDAO->getProducts();
    header("Content-Type: application/json");
    echo json_encode($products);

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>