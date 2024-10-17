<?php
session_start();

require("utilities.php");
function storeInputInSession()
{

}



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

function validateDigits($str, $length, $field)
{
    if (empty($str)) {
        return "$field cannot be empty.";
    } else if (!preg_match("/^\d{" . $length . "}$/", $str)) {
        return "$field must be exactly $length digits.";
    } else {
        return "";
    }
}

function validateRadio($fieldName)
{
    if (!isset($_POST[$fieldName])) {
        return "At least 1 option must be selected";
    }
    return "";

}

function validatePostAndState($post, $state)
{

}

function validateInput()
{
    $firstName = sanitise_input($_POST["first-name"]);
    $lastName = sanitise_input($_POST["last-name"]);
    // Validate first and last name
    $firstNameErrMsg = validateString($firstName, "/^[a-zA-Z ]+$/", 25, ["Alphabets", "Spaces"]);
    $lastNameErrMsg = validateString($lastName, "/^[a-zA-Z ]+$/", 25, ["Alphabets", "Spaces"]);

    // Validate email address
    $email = sanitise_input($_POST["email"]);
    $emailErrMsg = validateEmail($email);

    // Validate phone number
    $phoneNo = sanitise_input($_POST["phone-no"]);
    $phoneErrMsg = validateDigits($phoneNo, 10, "Phone number");

    // Validate if contact method is selected
    $contactMethErrMsg = validateRadio("contact-method");

    // Validate shipping address
    $shipStreet = sanitise_input($_POST["ship-street-add"]);
    $shipStreetErrMsg = validateString($shipStreet, "/^[a-zA-Z0-9 ]+$/", 40, ["Alphabets", "Numbers", "Spaces"]);

    $shipSuburb = sanitise_input($_POST["ship-street-suburb"]);
    $shipSuburbErrMsg = validateString($shipSuburb, "/^[a-zA-Z0-9 ]+$/", 40, ["Alphabets", "Numbers", "Spaces"]);

    // Check state and postcode combination


    // Validate billing address
}

if (isset($_POST["first-name"])) {
    validateInput();
} else {
    header("location:enquire.php");
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
                <p>A$ <span id="subtotal">0.00</span></p>
            </div>
            <!-- Horizontal line -->
            <hr>

            <!-- Payment form -->
            <form id="payment-form" method="post" action="https://mercury.swin.edu.au/it000000/formtest.php">
                <fieldset id="card-information">
                    <legend class="block-legend">Card Information</legend>

                    <!-- Card Type Group -->
                    <div class="form-group" id="card-type-container">
                        <label>Card Type</label>
                        <div id="card-type-options">
                            <input type="radio" id="visa" name="card-type" value="visa" title="Visa">
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

                            <input type="radio" id="mastercard" name="card-type" value="mastercard" title="MasterCard">
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

                            <input type="radio" id="amex" name="card-type" value="amex" title="American Express">
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
                        <span class="error-msg"></span>
                    </div>

                    <!-- Card Name Group -->
                    <div class="form-group" id="card-name-container">
                        <label for="card-name">Name on Card</label>
                        <input type="text" class="has-error-span" id="card-name" name="card-name"
                            placeholder="Full Name">
                        <span class="error-msg"></span>
                    </div>

                    <!-- Card Number Group -->
                    <div class="form-group" id="card-number-container">
                        <label for="card-number">Card Number</label>
                        <input type="text" id="card-number" class="has-error-span" name="card-number"
                            placeholder="XXXX XXXX XXXX XXXX">
                        <span class="error-msg"></span>
                    </div>

                    <!-- Expiry Date Group -->
                    <div class="form-group" id="expiry-date-container">
                        <label for="expiry-date">Expiry Date (MM-YY)</label>
                        <input type="text" id="expiry-date" class="has-error-span" name="expiry-date"
                            placeholder="MM-YY">
                        <span class="error-msg"></span>
                    </div>

                    <!-- CVV Group -->
                    <div class="form-group" id="cvv-container">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" class="has-error-span" name="cvv" placeholder="XXX">
                        <span class="error-msg"></span>
                    </div>

                </fieldset>
                <!-- Group for form actions -->
                <div class="form-act">
                    <input type="submit" value="Check-Out">
                    <button type="button" id="cancelBtn">Cancel</button>
                </div>

                <!-- Hidden inputs -->
                <input type="hidden" id="first-name" name="first-name">
                <input type="hidden" id="last-name" name="last-name">
                <input type="hidden" id="email" name="email">
                <input type="hidden" id="phone-no" name="phone-no">
                <input type="hidden" name="contact-method" id="contact-method">
                <input type="hidden" name="ship-street-add" id="ship-street-add">
                <input type="hidden" name="ship-street-suburb" id="ship-street-suburb">
                <input type="hidden" name="ship-street-state" id="ship-street-state">
                <input type="hidden" name="ship-street-post" id="ship-street-post">
                <input type="hidden" name="use-ship-check" id="use-ship-check">


            </form>
        </aside>


        <!-- Customer Info Container -->
        <div id="customer-info-container">
            <h2>Your Details</h2>
            <div class="info-group">
                <h4>Personal Details</h4>
                <p><strong>Name:</strong> <span id="full-name"></span></p>
                <p><strong>Email:</strong> <span id="email-span"></span></p>
                <p><strong>Phone Number:</strong> <span id="phone"></span></p>
                <p><strong>Contact Method:</strong> <span id="contact-method-span"></span></p>
            </div>
            <div class="info-group" id="delivery-address">
                <h4>Shipping Address</h4>
                <span id="ship-add"></span>
            </div>
            <div class="info-group" id="billing-address">
                <h4>Billing Address</h4>
                <span id="bill-add"></span>
            </div>
            <div class="info-group">
                <h4>Products</h4>
                <!-- Dynamically adding products into cart list -->
                <div id="cart-list">

                </div>
            </div>
            <div class="info-group">
                <h4>Comments</h4>
                <span id="comments-span"></span>
            </div>

        </div>


    </section>


    <!-- Include footer using php -->
    <?php
    include_once("footer.inc");
    ?>
</body>

</html>