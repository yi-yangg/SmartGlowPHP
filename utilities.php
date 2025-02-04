<?php

/**
 * PHP file that contains utility functions and classes. 
 * Database Access Object (DAO) classes are defined here for all database operations.
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file utilities.php
 */

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

// Function to validate a string based on regex, length, and allowed characters
function validateString($str, $regex, $length, $allowedChars)
{
    if (empty($str)) {
        return "Field is required.";
    } else if (strlen($str) > $length) {
        return "Input must be a maximum of $length characters.";
    } else if (!preg_match($regex, $str)) {
        return "Input must only contain: " . implode(", ", $allowedChars) . ".";
    } else {
        return "";
    }
}

// Function to validate digits based on length
function validateDigits($str, $length, $field)
{
    if ($length === null) {
        $regex = "/^\d+$/";
    } else {
        $regex = "/^\d{" . $length . "}$/";
    }

    if (empty($str)) {
        return "$field cannot be empty.";
    } else if (!preg_match($regex, $str)) {
        return $length !== null ? "$field must be exactly $length digits." : "$field must only contain digits.";
    } else {
        return "";
    }
}

// Function to validate radio buttons to check if at least 1 option is selected
function validateRadio($fieldName)
{
    if (!isset($_POST[$fieldName])) {
        return "At least 1 option must be selected";
    }
    return "";

}

// Function to display error message if it exists
function displayErrorIfExist($errorName, $fieldName)
{
    if (isset($_SESSION[$errorName][$fieldName])) {
        echo $_SESSION[$errorName][$fieldName];
    }
}

// Function to check if field has errors and display the value
function checkFieldErrors($error, $fieldName)
{
    if (isset($_SESSION[$fieldName])) {
        echo 'value="' . $_SESSION[$fieldName] . '"';
    }

    if (isset($_SESSION[$error][$fieldName]) && !empty($_SESSION[$error][$fieldName])) {
        echo ' class="has-error"';
    }
}


require_once("settings.php");

// BaseDAO class
class BaseDAO
{
    protected $conn;

    function __construct()
    {
        // Establish connection to the database
        global $host, $user, $pwd, $sql_db;
        try {
            $this->conn = @mysqli_connect($host, $user, $pwd, $sql_db);
        } catch (Exception $e) {

        }
    }

    function __destruct()
    {
        // Close the connection when the object is destroyed
        mysqli_close($this->conn);
    }

    // Check if the connection is valid
    function isConnValid()
    {
        // Return true if the connection is valid, false otherwise
        return $this->conn ? true : false;
    }
}

// ProductDAO class
class ProductDAO extends BaseDAO
{

    function __construct()
    {
        parent::__construct();
        if ($this->isConnValid()) {
            // Create products table if it does not exist
            $query = "CREATE TABLE IF NOT EXISTS products (
                product_id INT AUTO_INCREMENT PRIMARY KEY,
                product_name VARCHAR(100) NOT NULL,
                UNIQUE(product_name)
            )";
            mysqli_query($this->conn, $query);

            // Populate with default products
            $this->insertProduct("Smart Light Bulb");
            $this->insertProduct("Smart LED");
            $this->insertProduct("Smart Light Strip");
        }
    }

    // Get all products
    function getProducts()
    {
        if ($this->isConnValid()) {
            $query = "SELECT * FROM products ORDER BY product_name";
            $result = mysqli_query($this->conn, $query);
            $products = array();
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $products[] = $row['product_name'];
                }
            }
            return $products;
        }
        return null;

    }

    function insertProduct($product_name)
    {
        // Check if product already exists
        $productID = $this->getProductID($product_name);
        if ($productID) {
            return false;
        }

        // Check if connection is valid
        if ($this->isConnValid()) {
            // Query to insert product based on product name
            $query = "INSERT INTO products (product_name) VALUES ('$product_name')";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }

    // Get product ID based on product name
    function getProductID($product_name)
    {
        // Check if connection is valid
        if ($this->isConnValid()) {
            // Query to get product ID based on product name
            $query = "SELECT product_id FROM products WHERE product_name = '$product_name'";
            $result = mysqli_query($this->conn, $query);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                return $row['product_id'];
            }
        }
        return null;
    }

    // Delete product based on product name
    function deleteProduct($product_name)
    {
        // Check if connection is valid
        if ($this->isConnValid()) {
            // Query to delete product based on product name
            $query = "DELETE FROM products WHERE product_name = '$product_name'";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }

    // Update product name based on product name
    function updateProductName($product_name, $new_product_name)
    {
        // Check if connection is valid
        if ($this->isConnValid()) {
            // Query to update product name based on product name
            $query = "UPDATE products SET product_name = '$new_product_name' WHERE product_name = '$product_name'";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }

}

// ProductOptionDAO class
class ProductOptionDAO extends BaseDAO
{
    function __construct()
    {
        parent::__construct();
        if ($this->isConnValid()) {
            $query = "CREATE TABLE IF NOT EXISTS product_options (
                product_id INT NOT NULL,
                option_name VARCHAR(255) NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                PRIMARY KEY (product_id, option_name),
                FOREIGN KEY (product_id) REFERENCES products(product_id)
            );";
            mysqli_query($this->conn, $query);

            // Populate with default product options
            $this->insertDefaultOptions();
        }
    }
    // Insert default options for products
    private function insertDefaultOptions()
    {
        // Default options for products
        $defaultOptions = [
            "Smart Light Bulb" => [
                ["option_name" => "A19", "price" => 17.99],
                ["option_name" => "BR30", "price" => 22.99],
                ["option_name" => "GU10", "price" => 19.99]
            ],
            "Smart LED" => [
                ["option_name" => "2m", "price" => 19.99],
                ["option_name" => "5m", "price" => 27.99],
                ["option_name" => "10m", "price" => 42.99]
            ],
            "Smart Light Strip" => [
                ["option_name" => "1m", "price" => 12.99],
                ["option_name" => "2m", "price" => 21.99],
                ["option_name" => "5m", "price" => 33.99]
            ]
        ];

        // Insert default options for each product
        foreach ($defaultOptions as $productName => $options) {
            foreach ($options as $option) {
                $this->insertProductOption($productName, $option["option_name"], $option["price"]);
            }
        }
    }

    // Get product options based on product name
    function getProductOptions($product_name)
    {
        // Get product ID based on product name
        $product_id = (new ProductDAO())->getProductID($product_name);
        if (!$product_id) {
            return array();
        }

        // Query to get product options based on product ID
        $query = "SELECT * FROM product_options WHERE product_id = $product_id ORDER BY price";
        $result = mysqli_query($this->conn, $query);
        $product_options = array();
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $product_options[] = $row;
            }
        }
        return $product_options;
    }

    // Get option price based on product name and option name
    function getOptionPrice($product_name, $option_name)
    {
        // Get product ID based on product name
        $product_id = (new ProductDAO())->getProductID($product_name);
        if (!$product_id) {
            return null;
        }
        // Query to get option price based on product ID and option name
        $query = "SELECT price FROM product_options WHERE product_id = $product_id AND option_name = '$option_name'";
        $result = mysqli_query($this->conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['price'];
        }
        return null;
    }

    // Check if option exists based on product ID and option name
    function doesOptionExist($product_id, $option_name)
    {
        // Query to check if option exists based on product ID and option name
        $query = "SELECT * FROM product_options WHERE product_id = $product_id AND option_name = '$option_name'";
        $result = mysqli_query($this->conn, $query);
        return mysqli_num_rows($result) > 0;
    }

    // Insert product option based on product name, option name, and price
    function insertProductOption($product_name, $option_name, $price)
    {
        // Check if product exists
        $product_id = (new ProductDAO())->getProductID($product_name);
        if (!$product_id) {
            return false;
        }
        // Check if option already exists
        if ($this->doesOptionExist($product_id, $option_name)) {
            return false;
        }

        // Insert product option
        if ($this->isConnValid()) {
            $query = "INSERT INTO product_options (product_id, option_name, price) VALUES ($product_id, '$option_name', $price)";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }

    // Update product option based on product name, option name, new option name, and new price
    function updateProductOption($product_name, $option_name, $new_option_name, $new_price)
    {
        $product_id = (new ProductDAO())->getProductID($product_name);
        if (!$product_id) {
            return false;
        }
        // Check if connection is valid
        if ($this->isConnValid()) {
            // Query to update product option based on product ID, option name, new option name, and new price
            $query = "UPDATE product_options SET option_name = '$new_option_name', price = $new_price WHERE product_id = $product_id AND option_name = '$option_name'";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }

    // Delete product option based on product name and option name
    function deleteProductOption($product_name, $option_name)
    {
        $product_id = (new ProductDAO())->getProductID($product_name);
        if (!$product_id) {
            return false;
        }
        // Check if connection is valid
        if ($this->isConnValid()) {
            // Query to delete product option based on product ID and option name
            $query = "DELETE FROM product_options WHERE product_id = $product_id AND option_name = '$option_name'";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }
}

// OrderDAO class
class OrderDAO extends BaseDAO
{
    function __construct()
    {
        parent::__construct();
        // Create orders table if it does not exist
        if ($this->isConnValid()) {
            $query = "CREATE TABLE IF NOT EXISTS orders (
                order_id INT AUTO_INCREMENT PRIMARY KEY,
                order_name VARCHAR(100) NOT NULL,
                order_email VARCHAR(100) NOT NULL,
                order_phone VARCHAR(20) NOT NULL,
                order_contact_method ENUM('EMAIL', 'PHONE', 'POST') NOT NULL,
                order_address VARCHAR(255) NOT NULL,
                order_billing_address VARCHAR(255),
                order_comments VARCHAR(255),
                order_cost DECIMAL(10, 2) NOT NULL,
                order_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                order_status ENUM('PENDING', 'FULFILLED', 'PAID', 'ARCHIVED') NOT NULL DEFAULT 'PENDING',
                order_card_type ENUM('VISA', 'MASTERCARD', 'AMEX'),
                order_card_number VARCHAR(30),
                order_card_expiry VARCHAR(5),
                order_card_cvc VARCHAR(3)
            );";
            mysqli_query($this->conn, $query);
        }
    }

    // Get order based on order ID
    function getOrder($order_id)
    {
        if ($this->isConnValid()) {
            $query = "SELECT * FROM orders WHERE order_id = $order_id";
            $result = mysqli_query($this->conn, $query);
            if ($result) {
                return mysqli_fetch_assoc($result);
            }
        }
        return null;
    }

    // Get all orders
    function getOrders()
    {
        if ($this->isConnValid()) {
            $query = "SELECT * FROM orders ORDER BY order_cost, order_time";
            $result = mysqli_query($this->conn, $query);
            $orders = array();
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $orders[] = $row;
                }
            }
            return $orders;
        }
        return null;
    }

    // Search orders based on customer name, product name, and order status
    function searchOrders($customer_name, $product_name, $order_status)
    {
        if ($this->isConnValid()) {
            $query = "SELECT DISTINCT o.* FROM orders o 
            JOIN order_items oi 
            ON o.order_id = oi.order_id 
            JOIN products p 
            ON oi.product_id = p.product_id 
            WHERE o.order_name LIKE '%$customer_name%' 
            AND p.product_name LIKE '%$product_name%' 
            AND o.order_status LIKE '%$order_status%'
            ORDER BY order_cost, order_time";

            $result = mysqli_query($this->conn, $query);
            $orders = array();
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $orders[] = $row;
                }
            }
            return $orders;
        }
        return null;
    }

    // Insert order and return order ID
    function insertOrder($order_name, $order_email, $order_phone, $order_contact_method, $order_address, $order_billing_address, $order_comments, $order_cost)
    {
        if ($this->isConnValid()) {
            $query = "INSERT INTO orders (order_name, order_email, order_phone, order_contact_method, order_address, order_billing_address, order_comments, order_cost) VALUES ('$order_name', '$order_email', '$order_phone', '$order_contact_method', '$order_address', '$order_billing_address', '$order_comments', $order_cost)";
            if (mysqli_query($this->conn, $query)) {
                return mysqli_insert_id($this->conn);
            }
        }
        return null;
    }

    // Update payment method based on order ID
    function updatePaymentMethod($order_id, $order_card_type, $order_card_number, $order_card_expiry, $order_card_cvc)
    {
        if ($this->isConnValid()) {
            $query = "UPDATE orders SET order_card_type = '$order_card_type', order_card_number = '$order_card_number', order_card_expiry = '$order_card_expiry', order_card_cvc = '$order_card_cvc' WHERE order_id = $order_id";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }

    // Update order status based on order ID
    function updateOrderStatus($order_id, $order_status)
    {
        if ($this->isConnValid()) {
            $query = "UPDATE orders SET order_status = '$order_status' WHERE order_id = $order_id";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }

    function updateOrder($order_id, $order_name, $order_email, $order_phone, $order_contact_method, $order_address, $order_billing_address, $order_comments, $order_cost)
    {
        if ($this->isConnValid()) {
            $query = "UPDATE orders SET order_name = '$order_name', order_email = '$order_email', order_phone = '$order_phone', order_contact_method = '$order_contact_method', order_address = '$order_address', order_billing_address = '$order_billing_address', order_comments = '$order_comments', order_cost = $order_cost WHERE order_id = $order_id";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }

    // Delete order and orderItem based on order ID
    function deleteOrder($order_id)
    {
        (new OrderItemDAO())->deleteOrderItem($order_id);

        if ($this->isConnValid()) {
            $query = "DELETE FROM orders WHERE order_id = $order_id";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }



}

// OrderItemDAO class
class OrderItemDAO extends BaseDAO
{
    function __construct()
    {
        parent::__construct();
        if ($this->isConnValid()) {
            $query = "CREATE TABLE IF NOT EXISTS order_items (
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                option_name VARCHAR(255) NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                PRIMARY KEY (order_id, product_id, option_name),
                FOREIGN KEY (order_id) REFERENCES orders(order_id),
                FOREIGN KEY (product_id) REFERENCES products(product_id)
            );";
            mysqli_query($this->conn, $query);
        }
    }

    // Get order items based on order ID
    function getOrderItems($order_id)
    {
        if ($this->isConnValid()) {
            $query = "SELECT oi.order_id, p.product_name, oi.option_name, oi.quantity, oi.price 
            FROM order_items oi 
            JOIN products p 
            ON oi.product_id = p.product_id 
            WHERE oi.order_id = $order_id";
            $result = mysqli_query($this->conn, $query);
            $order_items = array();
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $order_items[] = $row;
                }
            }
            return $order_items;
        }
        return null;
    }

    // Insert order item based on order ID, product ID, option name, quantity, and price
    function insertOrderItem($order_id, $product_name, $option_name, $quantity, $price)
    {
        $product_id = (new ProductDAO())->getProductID($product_name);
        if (!$product_id) {
            return false;
        }
        if ($this->isConnValid()) {
            $query = "INSERT INTO order_items (order_id, product_id, option_name, quantity, price) VALUES ($order_id, $product_id, '$option_name', $quantity, $price)";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }

    // Delete order item based on order ID
    function deleteOrderItem($order_id)
    {
        if ($this->isConnValid()) {
            $query = "DELETE FROM order_items WHERE order_id = $order_id";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }
}

// UserDAO class
class UserDAO extends BaseDAO
{
    function __construct()
    {
        parent::__construct();
        if ($this->isConnValid()) {
            $query = "CREATE TABLE IF NOT EXISTS users (
                user_id VARCHAR(255) PRIMARY KEY,
                user_password VARCHAR(255) NOT NULL,
                user_name VARCHAR(255) NOT NULL,
                user_role ENUM('MANAGER', 'USER') NOT NULL DEFAULT 'USER'
            );";
            mysqli_query($this->conn, $query);
        }
    }

    // Get user based on user ID, password, and role
    function getUser($user_id, $password, $role)
    {
        if ($this->isConnValid()) {
            $query = "SELECT * FROM users WHERE user_id = '$user_id' AND user_password = '$password' AND user_role = '$role'";
            $result = mysqli_query($this->conn, $query);
            if ($result) {
                return mysqli_fetch_assoc($result);
            }
        }
        return null;
    }

    // Get user based on user ID
    function getUserById($user_id)
    {
        if ($this->isConnValid()) {
            $query = "SELECT * FROM users WHERE user_id = '$user_id'";
            $result = mysqli_query($this->conn, $query);
            if ($result) {
                return mysqli_fetch_assoc($result);
            }
        }
        return null;
    }

    // Insert user based on user ID, password, name and role
    function insertUser($user_id, $password, $name, $role)
    {
        if ($this->isConnValid()) {
            $query = "INSERT INTO users (user_id, user_password, user_name, user_role) VALUES ('$user_id', '$password', '$name', '$role')";
            if (mysqli_query($this->conn, $query)) {
                return true;
            }
        }
        return false;
    }
}
?>