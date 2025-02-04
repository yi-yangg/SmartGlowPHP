<?php
/**
 * Receipt page for SmartGlow purchase. Displays all order details and products purchased.
 * Redirected from payment.php after successful payment. Only accessible via POST request 
 * from payment.php. If accessed directly, user will be redirected back to index.php.
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file receipt.php
 */

require_once("utilities.php");
session_start();
// Check if file is accessed directly
if (!isset($_SESSION["isPaid"])) {
    // Redirect back to index.php
    header("location: index.php");
    exit();
}

unset($_SESSION["isPaid"]);

// Get order ID from session storage
$orderID = $_SESSION["orderNo"];

// Before removing all session variables, re-set the user_id and user_type session variables
$userID = "";
$userType = "";

if (isset($_SESSION["user_id"])) {
    $userID = $_SESSION["user_id"];
    $userType = $_SESSION["user_type"];
}


// Remove all session variables
session_unset();
// Re-set user_id and user_type session variables
if (!empty($userID)) {
    $_SESSION["user_id"] = $userID;
    $_SESSION["user_type"] = $userType;
}

// Destroy the session
session_destroy();

// Get order from MySQL database
$orderDAO = new OrderDAO();
$orderItemDAO = new OrderItemDAO();

$order = $orderDAO->getOrder($orderID);
$orderItems = $orderItemDAO->getOrderItems($orderID);

// Mask card number
function maskCardNumber($cardNumber)
{
    $maskedCardNumber = substr($cardNumber, -4);
    $maskedCardNumber = "**** **** **** " . $maskedCardNumber;
    return $maskedCardNumber;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="SmartGlow purchase receipt.">
    <meta name="keywords" content="SmartGlow, Receipt, Purchase">
    <meta name="author" content="Chong Yi Yang">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt | SmartGlow</title>
    <!-- Icon beside title link -->
    <link rel="icon" href="images/smartglow.ico" type="image/x-icon">

    <!-- Link for CSS style sheet -->
    <link rel="stylesheet" href="styles/style.css">
</head>

<body id="receipt-body">
    <!-- Include header and timer banner -->
    <?php
    include_once("header.inc");
    include_once("timer.inc");
    ?>

    <!-- Main content for receipt -->
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Order Receipt</h1>
            <p>Order ID: <?php echo $order["order_id"] ?> | Date & Time: <?php echo $order["order_time"] ?></p>
        </div>

        <!-- Customer Information -->
        <div class="customer-info">
            <h2>Customer Information</h2>
            <div class="details-row">
                <div>
                    <label>Name:</label>
                    <p><?php echo $order["order_name"] ?></p>
                    <label>Email:</label>
                    <p><?php echo $order["order_email"] ?></p>
                    <label>Phone:</label>
                    <p><?php echo $order["order_phone"] ?></p>
                </div>
                <div>
                    <label>Contact Method:</label>
                    <p><?php echo $order["order_contact_method"] ?></p>

                    <label>Address:</label>
                    <p><?php echo $order["order_address"] ?></p>

                    <?php
                    if (!empty($order["order_billing_address"])) {
                        echo "<label>Billing Address:</label>
                        <p>" . $order["order_billing_address"] . "</p>";
                    }
                    ?>

                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="payment-details">
            <h2>Payment Information</h2>
            <div class="details-row">
                <div>
                    <label>Total Cost:</label>
                    <p>A$<?php echo $order["order_cost"] ?></p>
                    <label>Status:</label>
                    <p><?php echo $order["order_status"] ?></p>
                </div>
                <div class="payment-method">
                    <label>Card Type:</label>
                    <p>
                        <?php echo ($order["order_status"] === "PENDING") ? "Not available" : $order["order_card_type"] ?>
                    </p>
                    <label>Card Number:</label>
                    <p>
                        <?php echo ($order["order_status"] === "PENDING") ? "Not available" : maskCardNumber($order["order_card_number"]) ?>
                    </p>
                    <label>Expiry Date:</label>
                    <p>
                        <?php echo ($order["order_status"] === "PENDING") ? "Not available" : $order["order_card_expiry"] ?>
                    </p>
                </div>
            </div>
        </div>

        <h2>Additional Comments</h2>
        <p>
            <?php
            if (empty($order["order_comments"])) {
                echo "No additional comments";
            } else
                echo html_entity_decode($order["order_comments"]);
            ?>
        </p>

        <!-- Order Items Table -->
        <h2>Order Items</h2>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($orderItems as $orderItem) {
                    echo '<tr>
                    <td>
                        <strong>' . $orderItem["product_name"] . '</strong><br>
                        <span class="option">' . $orderItem["option_name"] . '</span>
                    </td>
                    <td>' . $orderItem["quantity"] . '</td>
                    <td>A$' . $orderItem["price"] . '</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <div class="total">
            Total: A$<?php echo $order["order_cost"] ?>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <p>Thank you for your purchase!</p>
        </div>

    </div>
    <?php
    include_once("footer.inc");
    ?>
</body>