<?php
/**
 * PHP file that processes the payment and updates the order status in the database
 * Once payment details is filled in, it will redirect to process_order.php, where 
 * the inputs will be validated and stored in the database. The order status will be
 * initially set to "Pending" and will be updated to "Paid" if the payment is successful.
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file payment.php
 */

session_start();

require("utilities.php");

// Store all user input in enquire.php into session
function storeInputInSession()
{
    $_SESSION["firstName"] = sanitise_input($_POST["first-name"]);
    $_SESSION["lastName"] = sanitise_input($_POST["last-name"]);
    $_SESSION["email"] = sanitise_input($_POST["email"]);
    $_SESSION["phoneNo"] = sanitise_input($_POST["phone-no"]);
    if (isset($_POST["contact-method"])) {
        $_SESSION["contactMethod"] = sanitise_input($_POST["contact-method"]);
    }
    $_SESSION["shipStreet"] = sanitise_input($_POST["ship-street-add"]);
    $_SESSION["shipSuburb"] = sanitise_input($_POST["ship-street-suburb"]);
    $_SESSION["shipState"] = sanitise_input($_POST["ship-street-state"]);
    $_SESSION["shipPost"] = sanitise_input($_POST["ship-street-post"]);
    $_SESSION["useShipCheck"] = isset($_POST["use-ship-check"]) ? true : false;

    if (!isset($_POST["use-ship-check"])) {
        $_SESSION["billStreet"] = sanitise_input($_POST["bill-street-add"]);
        $_SESSION["billSuburb"] = sanitise_input($_POST["bill-street-suburb"]);
        $_SESSION["billState"] = sanitise_input($_POST["bill-street-state"]);
        $_SESSION["billPost"] = sanitise_input($_POST["bill-street-post"]);
    }

    // Store product number
    $_SESSION["productNoArr"] = getAllProductNo();

    foreach ($_SESSION["productNoArr"] as $productNo) {
        $_SESSION["product-$productNo"] = sanitise_input($_POST["product-$productNo"]);
        if (!isset($_POST["option-$productNo"]))
            continue;
        $_SESSION["option-$productNo"] = sanitise_input($_POST["option-$productNo"]);
        if (!isset($_POST["quantity-$productNo"]))
            continue;
        $_SESSION["quantity-$productNo"] = sanitise_input($_POST["quantity-$productNo"]);
    }

    $_SESSION["comment"] = sanitise_input($_POST["comment"]);
}

// Validate email address format
function validateEmail($email)
{
    if (empty($email)) {
        return "Email is required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    } else {
        return "";
    }
}

// Validate postcode and state combination
function validatePostAndState($post, $state)
{
    $postErrMsg = validateDigits($post, 4, "Postcode");
    $stateErrMsg = "";
    // Mapping of states to valid postcodes
    $statePostArr = [
        "VIC" => [3, 8],
        "NSW" => [1, 2],
        "QLD" => [4, 9],
        "NT" => [0],
        "WA" => [6],
        "SA" => [5],
        "TAS" => [7],
        "ACT" => [0],
    ];
    // Check if state is empty or invalid
    if (empty($state)) {
        $stateErrMsg = "State is required.";
    } else if (!array_key_exists($state, $statePostArr)) {
        $stateErrMsg = "Invalid state.";
    }

    // If there are errors in state or postcode, return the errors
    if ($postErrMsg || $stateErrMsg) {
        return [$stateErrMsg, $postErrMsg];
    }

    $firstDigit = $post[0];
    $validPostcodes = $statePostArr[$state];
    // Check if first digit of postcode is valid for the state
    if (!in_array($firstDigit, $validPostcodes)) {
        $postErrMsg = "Invalid postcode for $state.";
    }

    return [$stateErrMsg, $postErrMsg];
}

// Get all product numbers from POST
function getAllProductNo()
{
    $productNoArr = [];
    foreach ($_POST as $key => $value) {
        // Check if key is a product number then extract the number and add to array
        if (preg_match("/^product-(\d+)$/", $key, $matches)) {
            $productNoArr[] = $matches[1];
        }
    }
    return $productNoArr;
}

// Validate product, option and quantity
function validateProduct($productNo)
{
    $productErrMsg = "";
    $optionErrMsg = "";
    $quantityErrMsg = "";

    // If option is not set, then product is not selected
    if (!isset($_POST["option-$productNo"])) {
        $productErrMsg = "Please select a product.";
        return [$productErrMsg, $optionErrMsg, $quantityErrMsg];
    }

    $product = sanitise_input($_POST["product-$productNo"]);
    // Check product is a valid product from MySQL
    $productDAO = new ProductDAO();
    $allProducts = $productDAO->getProducts();

    $foundProduct = array_filter($allProducts, function ($p) use ($product) {
        return strtolower($p) === strtolower($product);
    });


    if (empty($foundProduct)) {
        $productErrMsg = "Invalid product.";
        return [$productErrMsg, $optionErrMsg, $quantityErrMsg];
    }


    // If quantity is not set, then option is not selected
    if (!isset($_POST["quantity-$productNo"])) {
        $optionErrMsg = "Please select an option.";
        return [$productErrMsg, $optionErrMsg, $quantityErrMsg];
    }

    $option = sanitise_input($_POST["option-$productNo"]);
    $quantity = sanitise_input($_POST["quantity-$productNo"]);

    // If product is select, then validate if option is selected
    if (empty($productErrMsg) && empty($option)) {
        $optionErrMsg = "Please select an option.";
    }

    // Validate if product option exists in MySql
    $optionDAO = new ProductOptionDAO();
    $allOptions = $optionDAO->getProductOptions($product);

    // Filter options based on the selected product option
    $foundOptions = array_filter($allOptions, function ($o) use ($option) {
        return strtolower($o["option_name"]) === strtolower($option);
    });

    if (empty($foundOptions)) {
        $optionErrMsg = "Invalid option.";
        return [$productErrMsg, $optionErrMsg, $quantityErrMsg];
    }

    // Validate quantity
    $quantityErrMsg = validateDigits($quantity, null, "Quantity");

    return [$productErrMsg, $optionErrMsg, $quantityErrMsg];
}

// Validate all user input from enquire.php
function validateInput()
{
    $firstNameRaw = $_POST["first-name"];
    $lastNameRaw = $_POST["last-name"];
    // Validate first and last name
    $firstNameErrMsg = validateString($firstNameRaw, "/^[a-zA-Z ]+$/", 25, ["Alphabets", "Spaces"]);
    $lastNameErrMsg = validateString($lastNameRaw, "/^[a-zA-Z ]+$/", 25, ["Alphabets", "Spaces"]);

    // Validate email address
    $emailRaw = $_POST["email"];
    $emailErrMsg = validateEmail($emailRaw);

    // Validate phone number
    $phoneNoRaw = $_POST["phone-no"];
    $phoneErrMsg = validateDigits($phoneNoRaw, 10, "Phone number");

    // Validate if contact method is selected
    $contactMethErrMsg = validateRadio("contact-method");

    // Validate shipping address
    $shipStreetRaw = $_POST["ship-street-add"];
    $shipStreetErrMsg = validateString($shipStreetRaw, "/^[a-zA-Z0-9 ]+$/", 40, ["Alphabets", "Numbers", "Spaces"]);

    $shipSuburbRaw = $_POST["ship-street-suburb"];
    $shipSuburbErrMsg = validateString($shipSuburbRaw, "/^[a-zA-Z0-9 ]+$/", 40, ["Alphabets", "Numbers", "Spaces"]);

    // Check state and postcode combination
    $shipStateRaw = $_POST["ship-street-state"];
    $shipPostRaw = $_POST["ship-street-post"];
    list($shipStateErrMsg, $shipPostErrMsg) = validatePostAndState($shipPostRaw, $shipStateRaw);

    // Validate billing address
    $billStreetErrMsg = "";
    $billSuburbErrMsg = "";
    $billStateErrMsg = "";
    $billPostErrMsg = "";

    if (!isset($_POST["use-ship-check"])) {
        // Validate billing street address
        $billStreetRaw = $_POST["bill-street-add"];
        $billStreetErrMsg = validateString($billStreetRaw, "/^[a-zA-Z0-9 ]+$/", 40, ["Alphabets", "Numbers", "Spaces"]);

        // Validate billing suburb
        $billSuburbRaw = $_POST["bill-street-suburb"];
        $billSuburbErrMsg = validateString($billSuburbRaw, "/^[a-zA-Z0-9 ]+$/", 40, ["Alphabets", "Numbers", "Spaces"]);

        // Check billing address state and postcode combination
        $billStateRaw = $_POST["bill-street-state"];
        $billPostRaw = $_POST["bill-street-post"];
        list($billStateErrMsg, $billPostErrMsg) = validatePostAndState($billPostRaw, $billStateRaw);
    }

    // Validate products
    $productErrMsgArr = [];
    $optionErrMsgArr = [];
    $quantityErrMsgArr = [];

    $productNoArr = getAllProductNo();

    // Merge duplicate product and option
    $mergedProducts = [];
    foreach ($productNoArr as $productNo) {
        $product = $_POST["product-$productNo"];
        if (!isset($_POST["option-$productNo"]))
            continue;
        $option = $_POST["option-$productNo"];
        if (!isset($_POST["quantity-$productNo"]))
            continue;
        $quantity = $_POST["quantity-$productNo"];

        $key = $product . '-' . $option;
        if (isset($mergedProducts[$key])) {
            $mergedProducts[$key] += $quantity;
        } else {
            $mergedProducts[$key] = $quantity;
        }
    }

    // Update $_POST with merged quantities
    foreach ($productNoArr as $productNo) {
        $product = $_POST["product-$productNo"];
        if (!isset($_POST["option-$productNo"]) || !isset($_POST["quantity-$productNo"]))
            continue;
        $option = $_POST["option-$productNo"];
        $key = $product . '-' . $option;

        if (isset($mergedProducts[$key])) {
            $_POST["quantity-$productNo"] = $mergedProducts[$key];
            unset($mergedProducts[$key]); // Ensure only one productNo is updated
        } else {
            unset($_POST["product-$productNo"]);
            unset($_POST["option-$productNo"]);
            unset($_POST["quantity-$productNo"]);
            // Pop product number from array
            $productNoArr = array_filter($productNoArr, function ($p) use ($productNo) {
                return $p !== $productNo;
            });
        }
    }

    // For each of the product numbers, validate the product, option and quantity
    foreach ($productNoArr as $productNo) {
        // Validate chosen product
        list($productErrMsg, $optionErrMsg, $quantityErrMsg) = validateProduct($productNo);
        $productErrMsgArr[] = $productErrMsg;
        $optionErrMsgArr[] = $optionErrMsg;
        $quantityErrMsgArr[] = $quantityErrMsg;
    }

    // Combine all user basic information into an array
    $errorArr = [
        "firstName" => $firstNameErrMsg,
        "lastName" => $lastNameErrMsg,
        "email" => $emailErrMsg,
        "phoneNo" => $phoneErrMsg,
        "contactMethod" => $contactMethErrMsg,
        "shipStreet" => $shipStreetErrMsg,
        "shipSuburb" => $shipSuburbErrMsg,
        "shipState" => $shipStateErrMsg,
        "shipPost" => $shipPostErrMsg,
        "billStreet" => $billStreetErrMsg,
        "billSuburb" => $billSuburbErrMsg,
        "billState" => $billStateErrMsg,
        "billPost" => $billPostErrMsg,
    ];

    // Filter out non empty errors from the error array
    $nonEmptyErrors = array_filter($errorArr, function ($err) {
        return !empty($err);
    });


    // Check product errors
    $productErrors = array_filter(array_merge($productErrMsgArr, $optionErrMsgArr, $quantityErrMsgArr), function ($err) {
        return !empty($err);
    });


    storeInputInSession();
    // Store all errors in session
    $_SESSION["errors"] = $errorArr;
    $_SESSION["productErrors"] = $productErrMsgArr;
    $_SESSION["optionErrors"] = $optionErrMsgArr;
    $_SESSION["quantityErrors"] = $quantityErrMsgArr;
    if (!empty($nonEmptyErrors) || !empty($productErrors)) {
        header("location:enquire.php#purchase-form");
        exit();
    }

}

// Billing session info may not be replaced when the user resubmits the form
function clearBillingSessionInfo()
{
    if (isset($_SESSION["billStreet"])) {
        unset($_SESSION["billStreet"]);
        unset($_SESSION["billSuburb"]);
        unset($_SESSION["billState"]);
        unset($_SESSION["billPost"]);
    }
}

// Calculate total price of all products
function calculateTotalPrice()
{
    $totalPrice = 0;
    // For each product number, calculate the price and quantity
    foreach ($_SESSION["productNoArr"] as $productNo) {
        $product = $_SESSION["product-$productNo"];
        $option = $_SESSION["option-$productNo"];
        $quantity = $_SESSION["quantity-$productNo"];

        $price = getPrice($product, $option);
        // Add to total price
        $totalPrice += $price * $quantity;
    }
    $_SESSION["totalPrice"] = $totalPrice;
}

// If user has submitted the form from enquire.php, process the order
if (isset($_POST["first-name"])) {
    // Clear billing session info
    clearBillingSessionInfo();

    // Validate user input on enquire.php
    validateInput();

    // Calculate total price
    calculateTotalPrice();

    // Add/update order to orders table depending on if orderNo is set
    addToOrders(isset($_SESSION["orderNo"]));


} else {
    // If redirect back from process_order.php, don't redirect back to enquire.php
    if (isset($_SESSION["didPaymentFail"])) {
        unset($_SESSION["didPaymentFail"]);
    } else {
        header("location:enquire.php ");
    }
}

function addToOrders($isUpdate)
{
    // Create order and order items DAO objects
    $orderDAO = new OrderDAO();
    $orderItemDAO = new OrderItemDAO();

    // Get user information from session
    $name = sanitise_input($_SESSION["firstName"] . " " . $_SESSION["lastName"]);
    $email = sanitise_input($_SESSION["email"]);
    $phoneNo = sanitise_input($_SESSION["phoneNo"]);
    $contactMethod = sanitise_input($_SESSION["contactMethod"]);
    $address = sanitise_input($_SESSION["shipStreet"] . ", " . $_SESSION["shipSuburb"] . ", " . $_SESSION["shipState"] . ", " . $_SESSION["shipPost"]);
    $billAddress = $_SESSION["useShipCheck"] ? "" : sanitise_input($_SESSION["billStreet"] . ", " . $_SESSION["billSuburb"] . ", " . $_SESSION["billState"] . ", " . $_SESSION["billPost"]);
    $comment = sanitise_input($_SESSION["comment"]);
    $totalPrice = sanitise_input($_SESSION["totalPrice"]);


    if ($isUpdate) {
        $orderDAO->updateOrder($_SESSION["orderNo"], $name, $email, $phoneNo, $contactMethod, $address, $billAddress, $comment, $totalPrice);
        $orderID = $_SESSION["orderNo"];
    } else {
        // Insert order into orders table
        $orderID = $orderDAO->insertOrder($name, $email, $phoneNo, $contactMethod, $address, $billAddress, $comment, $totalPrice);
    }

    $orderItemDAO->deleteOrderItem($orderID);
    // Get all product numbers from session
    $productNoArr = $_SESSION["productNoArr"];
    foreach ($productNoArr as $productNo) {
        $product = $_SESSION["product-$productNo"];
        $option = $_SESSION["option-$productNo"];
        $quantity = $_SESSION["quantity-$productNo"];
        // Insert order item into order_items table
        $orderItemDAO->insertOrderItem($orderID, $product, $option, $quantity, number_format(getPrice($product, $option), 2));
    }

    // Store order number in session
    $_SESSION["orderNo"] = $orderID;
}

function getPrice($product, $option)
{
    $optionDAO = new ProductOptionDAO();

    $price = $optionDAO->getOptionPrice($product, $option);

    return $price ? $price : 0;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="SmartGlow payment">
    <meta name="keywords" content="SmartGlow, payment, billing, checkout">
    <meta name="author" content="Chong Yi Yang">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | SmartGlow</title>
    <!-- Icon beside title link -->
    <link rel="icon" href="images/smartglow.ico" type="image/x-icon">

    <!-- Link for CSS style sheet -->
    <link rel="stylesheet" href="styles/style.css">
    <script src="scripts/part2.js"></script>
    <script src="scripts/enhancements.js"></script>
    <script src="scripts/part2_payment.js"></script>
</head>

<body>
    <!-- Toast -->
    <div class="toast hide" id="toast-container">
        <div class="toast-body">
            <span id="toast-span"></span>
        </div>
        <span id="close">x</span>
    </div>

    <!-- Include header and timer banner -->
    <?php
    include_once("header.inc");
    include_once("timer.inc");
    ?>

    <h1 id="payment-heading">Checkout</h1>
    <!-- Payment section -->
    <section id="payment-section">
        <aside id="payment-form-container">
            <!-- Aside header -->
            <h3>Payment Details</h3>
            <!-- Subtotal container -->
            <div class="product-data">
                <h4>Subtotal</h4>
                <p>A$ <span id="subtotal">
                        <?php

                        echo number_format($_SESSION["totalPrice"], 2);

                        ?>
                    </span></p>
            </div>
            <!-- Horizontal line -->
            <hr>

            <!-- Payment form -->
            <form id="payment-form" method="post" action="process_order.php" novalidate="novalidate">
                <fieldset id="card-information">
                    <legend class="block-legend">Card Information</legend>

                    <!-- Card Type Group -->
                    <div class="form-group" id="card-type-container">
                        <label>Card Type</label>
                        <div id="card-type-options">
                            <input type="radio" id="visa" name="card-type" value="visa" title="Visa" <?php
                            echo (isset($_SESSION["cardType"]) && $_SESSION["cardType"] == "visa") ? "checked" : "";
                            ?>>
                            <label for="visa" title="Visa">
                                <!-- Visa Icon SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                                    <path fill="#1565C0"
                                        d="M45,35c0,2.209-1.791,4-4,4H7c-2.209,0-4-1.791-4-4V13c0-2.209,1.791-4,4-4h34c2.209,0,4,1.791,4,4V35z">
                                    </path>
                                    <path fill="#FFF"
                                        d="M15.186 19l-2.626 7.832c0 0-.667-3.313-.733-3.729-1.495-3.411-3.701-3.221-3.701-3.221L10.726 30v-.002h3.161L18.258 19H15.186zM17.689 30L20.56 30 22.296 19 19.389 19zM38.008 19h-3.021l-4.71 11h2.852l.588-1.571h3.596L37.619 30h2.613L38.008 19zM34.513 26.328l1.563-4.157.818 4.157H34.513zM26.369 22.206c0-.606.498-1.057 1.926-1.057.928 0 1.991.674 1.991.674l.466-2.309c0 0-1.358-.515-2.691-.515-3.019 0-4.576 1.444-4.576 3.272 0 3.306 3.979 2.853 3.979 4.551 0 .291-.231.964-1.888.964-1.662 0-2.759-.609-2.759-.609l-.495 2.216c0 0 1.063.606 3.117.606 2.059 0 4.915-1.54 4.915-3.752C30.354 23.586 26.369 23.394 26.369 22.206z">
                                    </path>
                                    <path fill="#FFC107"
                                        d="M12.212,24.945l-0.966-4.748c0,0-0.437-1.029-1.573-1.029c-1.136,0-4.44,0-4.44,0S10.894,20.84,12.212,24.945z">
                                    </path>
                                </svg>
                            </label>

                            <input type="radio" id="mastercard" name="card-type" value="mastercard" title="MasterCard" <?php
                            echo (isset($_SESSION["cardType"]) && $_SESSION["cardType"] == "mastercard") ? "checked" : "";
                            ?>>
                            <label for="mastercard" title="MasterCard">
                                <!-- MasterCard Icon SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                                    <path fill="#3F51B5"
                                        d="M45,35c0,2.209-1.791,4-4,4H7c-2.209,0-4-1.791-4-4V13c0-2.209,1.791-4,4-4h34c2.209,0,4,1.791,4,4V35z">
                                    </path>
                                    <path fill="#FFC107" d="M30 14A10 10 0 1 0 30 34A10 10 0 1 0 30 14Z"></path>
                                    <path fill="#FF3D00"
                                        d="M22.014,30c-0.464-0.617-0.863-1.284-1.176-2h5.325c0.278-0.636,0.496-1.304,0.637-2h-6.598C20.07,25.354,20,24.686,20,24h7c0-0.686-0.07-1.354-0.201-2h-6.598c0.142-0.696,0.359-1.364,0.637-2h5.325c-0.313-0.716-0.711-1.383-1.176-2h-2.973c0.437-0.58,0.93-1.122,1.481-1.595C21.747,14.909,19.481,14,17,14c-5.523,0-10,4.477-10,10s4.477,10,10,10c3.269,0,6.162-1.575,7.986-4H22.014z">
                                    </path>
                                </svg>
                            </label>

                            <input type="radio" id="amex" name="card-type" value="amex" title="American Express" <?php
                            echo (isset($_SESSION["cardType"]) && $_SESSION["cardType"] == "amex") ? "checked" : "";
                            ?>>
                            <label for="amex" title="American Express">
                                <!-- Amex Icon SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                                    <path fill="#1976D2"
                                        d="M45,35c0,2.209-1.791,4-4,4H7c-2.209,0-4-1.791-4-4V13c0-2.209,1.791-4,4-4h34c2.209,0,4,1.791,4,4V35z">
                                    </path>
                                    <path fill="#FFF"
                                        d="M22.255 20l-2.113 4.683L18.039 20h-2.695v6.726L12.341 20h-2.274L7 26.981h1.815l.671-1.558h3.432l.682 1.558h3.465v-5.185l2.299 5.185h1.563l2.351-5.095v5.095H25V20H22.255zM10.135 23.915l1.026-2.44 1.066 2.44H10.135zM37.883 23.413L41 20.018h-2.217l-1.994 2.164L34.86 20H28v6.982h6.635l2.092-2.311L38.767 27h2.21L37.883 23.413zM33.728 25.516h-4.011v-1.381h3.838v-1.323h-3.838v-1.308l4.234.012 1.693 1.897L33.728 25.516z">
                                    </path>
                                </svg>
                            </label>
                        </div>
                        <span class="error-msg">
                            <?php
                            displayErrorIfExist("paymentErrors", "cardType");
                            ?>
                        </span>
                    </div>

                    <!-- Card Name Group -->
                    <div class="form-group" id="card-name-container">
                        <label for="card-name">Name on Card</label>
                        <input type="text" id="card-name" name="card-name" placeholder="Full Name" <?php
                        checkFieldErrors("paymentErrors", "cardName");
                        ?>>
                        <span class="error-msg">
                            <?php
                            displayErrorIfExist("paymentErrors", "cardName");
                            ?>
                        </span>
                    </div>

                    <!-- Card Number Group -->
                    <div class="form-group" id="card-number-container">
                        <label for="card-number">Card Number</label>
                        <input type="text" id="card-number" name="card-number" placeholder="XXXX XXXX XXXX XXXX" <?php
                        checkFieldErrors("paymentErrors", "cardNumber");
                        ?>>
                        <span class="error-msg">
                            <?php
                            displayErrorIfExist("paymentErrors", "cardNumber");
                            ?>
                        </span>
                    </div>

                    <!-- Expiry Date Group -->
                    <div class="form-group" id="expiry-date-container">
                        <label for="expiry-date">Expiry Date (MM-YY)</label>
                        <input type="text" id="expiry-date" name="expiry-date" placeholder="MM-YY" <?php
                        checkFieldErrors("paymentErrors", "expiryDate");
                        ?>>
                        <span class="error-msg">
                            <?php
                            displayErrorIfExist("paymentErrors", "expiryDate");
                            ?>
                        </span>
                    </div>

                    <!-- CVV Group -->
                    <div class="form-group" id="cvv-container">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" placeholder="XXX" <?php
                        checkFieldErrors("paymentErrors", "cvv");
                        ?>>
                        <span class="error-msg">
                            <?php
                            displayErrorIfExist("paymentErrors", "cvv");
                            ?>
                        </span>
                    </div>

                </fieldset>
                <!-- Group for form actions -->

                <div class="form-act">
                    <input type="submit" value="Check-Out">
                    <a href="cancel_order.php" id="cancelBtn">Cancel</a>
                </div>


            </form>
        </aside>


        <!-- Customer Info Container -->
        <div id="customer-info-container">
            <h2>Your Details</h2>
            <div class="info-group">
                <h4>Personal Details</h4>
                <p><strong>Name:</strong> <?php echo $_SESSION["firstName"] . " " . $_SESSION["lastName"] ?></p>
                <p><strong>Email:</strong> <?php echo $_SESSION["email"] ?></p>
                <p><strong>Phone Number:</strong> <?php echo $_SESSION["phoneNo"] ?></p>
                <p><strong>Contact Method:</strong> <?php echo ucfirst($_SESSION["contactMethod"]) ?></p>
            </div>

            <?php
            echo '<div class="info-group" id="delivery-address"><h4>Shipping' . ($_SESSION["useShipCheck"] ? ' & Billing ' : ' ') . 'Address</h4>';
            echo $_SESSION["shipStreet"] . ", " . $_SESSION["shipSuburb"] . ", " . $_SESSION["shipState"] . ", " . $_SESSION["shipPost"] . '</div>';

            ?>
            <?php
            if (!$_SESSION["useShipCheck"]) {
                echo '<div class="info-group" id="billing-address"><h4>Billing Address</h4>';
                echo $_SESSION["billStreet"] . ", " . $_SESSION["billSuburb"] . ", " . $_SESSION["billState"] . ", " . $_SESSION["billPost"] . '</div>';
            }
            ?>
            <div class="info-group">
                <h4>Products</h4>
                <!-- Dynamically adding products into cart list -->
                <div id="cart-list">
                    <?php
                    foreach ($_SESSION["productNoArr"] as $productNo) {
                        $product = $_SESSION["product-$productNo"];
                        $option = $_SESSION["option-$productNo"];
                        $quantity = $_SESSION["quantity-$productNo"];

                        $price = getPrice($product, $option);

                        echo '
                        <div class="product-item">
                            <div class="product-details">
                                <h5>' . ucwords($product) . '</h5>
                                <div class="product-data">
                                    <span>' . strtoupper($option) . '</span>
                                    <span>Quantity: ' . $quantity . '</span>
                                </div>
                                <div class="product-price">
                                    <span>A$ ' . number_format($price, 2) . '</span>
                                </div>
                            </div>
                        </div>
                        ';

                    }
                    ?>
                </div>
            </div>
            <div class="info-group">
                <h4>Comments</h4>
                <p><?php echo $_SESSION["comment"] ?></p>
            </div>

        </div>


    </section>


    <!-- Include footer using php -->
    <?php
    include_once("footer.inc");
    ?>
</body>

</html>