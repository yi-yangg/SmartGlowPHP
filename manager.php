<?php
/**
 * Manager page for managers to update and delete orders from the MySQL database
 * Only accessible to managers and displays all orders in a table format
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file manager.php
 */


session_start();
// Check if the user logged in is a manager
if (!isset($_SESSION["user_type"]) || $_SESSION["user_type"] != "manager") {
    header("location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="SmartGlow manager page for managers to update and delete orders">
    <meta name="keywords" content="SmartGlow, Manager, Update and delete orders">
    <meta name="author" content="Chong Yi Yang">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager | SmartGlow</title>
    <!-- Icon beside title link -->
    <link rel="icon" href="images/smartglow.ico" type="image/x-icon">

    <!-- Link for CSS style sheet -->
    <link rel="stylesheet" href="styles/style.css">
    <script src="scripts/manager.js"></script>
</head>

<body id="manager-body">
    <!-- Include header and timer banner -->
    <?php
    include_once("header.inc");
    include_once("timer.inc");
    ?>

    <section class="report-container">
        <h1>Manager Order Report</h1>
        <form class="search-form" action="manager.php" method="post">
            <div class="filter">
                <label for="customerName">Customer Name:</label>
                <input type="text" id="customerName" name="customerName" placeholder="Enter first or last name">
            </div>
            <div class="filter">
                <label for="product">Product:</label>
                <input type="text" id="product" name="product" placeholder="Enter product name">
            </div>
            <div class="filter">
                <label for="status">Order Status:</label>
                <select id="status" name="status">
                    <option value="">Select Status</option>
                    <option value="pending">Pending</option>
                    <option value="fulfilled">Fulfilled</option>
                    <option value="paid">Paid</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <div class="filter">
                <input type="submit" value="Search">
            </div>
        </form>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Order Date & Time</th>
                    <th>Total Cost</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once("utilities.php");
                $orderDAO = new OrderDAO();
                // If there is no search query then show all orders
                if (!isset($_POST["customerName"])) {
                    $orders = $orderDAO->getOrders();
                } else {
                    $orders = $orderDAO->searchOrders($_POST["customerName"], $_POST["product"], $_POST["status"]);
                }
                foreach ($orders as $order) {
                    echo "<tr>";
                    echo "<td>#" . $order["order_id"] . "</td>";
                    echo "<td>" . $order["order_time"] . "</td>";
                    echo "<td>A$" . $order["order_cost"] . "</td>";
                    echo "<td>" . $order["order_status"] . "</td>";
                    echo "<td><button class='expand-btn' data-id='" . $order["order_id"] . "'><span class='expand-icon'>&#x25BC;</span> Details</button></td>";
                    echo "</tr>";

                    echo '<tr id="order-' . $order["order_id"] . '" class="order-details">
                    <td colspan="5">
                        <h2>Customer Details</h2>
                        <p><strong>Name:</strong> ' . $order["order_name"] . '</p>
                        <p><strong>Email:</strong> ' . $order["order_email"] . '</p>
                        <p><strong>Phone:</strong> ' . $order["order_phone"] . '</p>
                        <p><strong>Contact Method:</strong> ' . $order["order_contact_method"] . '</p>
                        <p><strong>Address:</strong> ' . $order["order_address"] . '</p><p>';
                    echo (!empty($order["order_billing_address"])) ? '<strong>Billing Address:</strong> ' . $order["order_billing_address"] . '</p>' : '';

                    echo '<h2>Purchased Products</h2>
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Cost</th>
                                </tr>
                            </thead>
                            <tbody>';
                    // Get products for order
                    $products = (new OrderItemDAO())->getOrderItems($order["order_id"]);

                    foreach ($products as $product) {
                        echo '<tr>
                    <td>
                        <strong>' . $product["product_name"] . '</strong><br>
                        <span class="option">' . $product["option_name"] . '</span>
                    </td>
                    <td>' . $product["quantity"] . '</td>
                    <td>A$' . $product["price"] . '</td></tr>';
                    }
                    echo '</tbody></table>';

                    // Status Update Form
                    echo '
                <div class="form-container">
                    <div>
                        <h3>Update Order Status</h3>';
                    echo '
                    <form method="post" action="update_status.php" class="status-update-form">
                        <input type="hidden" name="order-id" value="' . $order["order_id"] . '">
                        <select name="order-status">
                            <option value="pending" selected>Pending</option>
                            <option value="fulfilled">Fulfilled</option>
                            <option value="paid">Paid</option>
                            <option value="archived">Archived</option>
                        </select>
                        <input type="submit" class="btn update-btn" value="Update Status">
                    </form></div>';

                    // Delete Order Button (Only available if status is 'pending')
                    if (strtolower($order["order_status"]) == 'pending') {
                        echo '<div>
                        <h3>Delete Order</h3>';
                        echo '
                    <form method="post" action="delete_order.php" class="delete-order-form">
                        <input type="hidden" name="order-id" value="' . $order["order_id"] . '">
                        <input type="submit" class="btn delete-btn" value="Delete Order">
                    </form>
                    </div>'
                        ;
                    }
                    echo '</div></td></tr>';
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Include footer -->
    <?php
    include_once("footer.inc");
    ?>
</body>