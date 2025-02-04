<?php
/**
 * PHP file that processes the payment details and stores them in the session.
 * Validates the payment details and redirects back to payment.php if there are errors.
 * If there are no errors, the payment details are stored in the session and the order status is updated to "Paid".
 * Payment details are also updated in the orders table in the MySQL database. Redirects to receipt.php.
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file process_order.php
 */

session_start();
require_once("utilities.php");

// Check if payment name matches user's name
function doesNameMatch($readOnlyName, $nameToCheck)
{
    // Split names into parts
    $readOnlyNameParts = explode(" ", $readOnlyName);
    $checkNameParts = explode(" ", $nameToCheck);

    // Check if all parts of the name to check are in the read-only name
    foreach ($checkNameParts as $namePart) {
        // Search for the name part in the read-only name
        $index = array_search($namePart, $readOnlyNameParts);
        // If the name part is found, remove it from the read-only name
        if ($index !== false) {
            array_splice($readOnlyNameParts, $index, 1); // Remove 1 element from the index
        }
    }
    // If all parts of the name to check are found in the read-only name, the name matches
    return count($readOnlyNameParts) === 0;
}

// Validate card name
function validateCardName()
{
    // Get card name from POST request
    $cardNameRaw = $_POST["card-name"];
    $cardName = sanitise_input($cardNameRaw);

    // Check if card name is alphabets and spaces only
    $errMsg = validateString($cardNameRaw, "/^[a-zA-Z ]+$/", 40, ["Alphabets", "Spaces"]);

    // Check if card name is empty
    if (empty($errMsg)) {
        // Check if card name is the same as the user's name in session
        $username = $_SESSION["firstName"] . " " . $_SESSION["lastName"];
        if (!doesNameMatch($username, $cardName)) {
            $errMsg = "Card name must match the user's name.";
        }
    }

    return $errMsg;
}

// Validate card number based on card type
function validateCardNoWithType($cardType, $cardNumber)
{
    // Get first 2 digits of card number
    $first2 = substr($cardNumber, 0, 2);
    // Check card type and validate card number
    switch ($cardType) {
        case "visa":
            // If card type is visa, input length must be 16 and first digit 4
            if (strlen($cardNumber) != 16 || $cardNumber[0] !== "4") {
                return "Invalid Visa format.";
            }
            break;
        case "mastercard":
            // If card type is mastercard, input length must be 16 and first 2 digits between 51 and 55
            if (strlen($cardNumber) != 16 || ($first2 < 51 || $first2 > 55)) {
                return "Invalid MasterCard format.";
            }
            break;
        case "amex":
            // If amex, input length must be 15 and first 2 digits either 34 or 37
            if (strlen($cardNumber) != 15 || ($first2 != 34 && $first2 != 37)) {
                return "Invalid American Express format.";
            }
            break;
        default:
            return "Invalid card type.";
    }

    return ""; // No error
}

// Validate card number
function validateCardNumber()
{
    $cardNumberRaw = $_POST["card-number"];
    // Replace spaces with empty string
    $cardNumberRaw = str_replace(" ", "", $cardNumberRaw);

    // Check if card number is 16 digits
    $errMsg = validateDigits($cardNumberRaw, 16, "Card number");
    if (!empty($errMsg)) {
        // Check if card number is 15 digits for American Express
        $errMsg = validateDigits($cardNumberRaw, 15, "Card number");
    }

    // If no error, validate card number based on card type
    if (empty($errMsg)) {
        if (isset($_POST["card-type"])) {
            $errMsg = validateCardNoWithType($_POST["card-type"], $cardNumberRaw);
        } else {
            $errMsg = "Card type is required.";
        }
    }
    return $errMsg;

}

// Validate expiry date
function validateExpiryDate()
{
    $expiryDateRaw = $_POST["expiry-date"];
    $errMsg = "";

    // Regex for expiry date format 01 to 12 for month and any 2 digits for year
    $expiryRegex = "/^(0[1-9]|1[0-2])-(\d{2})$/";

    if (empty($expiryDateRaw)) {
        $errMsg = "Card expiry date cannot be empty.";
    } elseif (!preg_match($expiryRegex, $expiryDateRaw)) {
        $errMsg = "Invalid format or month (Use MM-YY format).";
    } else {
        // Split expiry string and convert to numbers
        list($expMonth, $expYear) = array_map('intval', explode("-", $expiryDateRaw));

        // Get today's month and year
        $today = new DateTime();
        $month = $today->format('m');
        $year = $today->format('y');

        // Check if expiry year is in the past or if year is the same, if the month is in the past
        if ($expYear < $year || ($expYear == $year && $expMonth < $month)) {
            $errMsg = "The card provided has expired.";
        }
    }

    return $errMsg;
}

// Store payment details in session
function storePaymentDetails()
{
    if (isset($_POST["card-type"])) {
        $_SESSION["cardType"] = sanitise_input($_POST["card-type"]);
    }

    $_SESSION["cardName"] = sanitise_input($_POST["card-name"]);
    $_SESSION["cardNumber"] = sanitise_input($_POST["card-number"]);
    $_SESSION["expiryDate"] = sanitise_input($_POST["expiry-date"]);
    $_SESSION["cvv"] = sanitise_input($_POST["cvv"]);
}

// Validate all payment details from POST request
function validatePaymentMethod()
{
    // Validate card type
    $cardTypeErrMsg = validateRadio("card-type");

    // Validate card name
    $cardNameErrMsg = validateCardName();

    // Validate card number
    $cardNumberErrMsg = validateCardNumber();

    // Validate expiry date
    $cardExpiryErrMsg = validateExpiryDate();

    // Validate CVV
    $cardCVVErrMsg = validateDigits($_POST["cvv"], 3, "CVV");

    $errArr = [
        "cardType" => $cardTypeErrMsg,
        "cardName" => $cardNameErrMsg,
        "cardNumber" => $cardNumberErrMsg,
        "expiryDate" => $cardExpiryErrMsg,
        "cvv" => $cardCVVErrMsg
    ];

    // Filter out empty errors
    $nonEmptyErrors = array_filter($errArr, function ($err) {
        return !empty($err);
    });

    // Store payment details in session
    storePaymentDetails();
    $_SESSION["paymentErrors"] = $errArr;

    // If there are errors, redirect back to payment.php
    if (count($nonEmptyErrors) > 0) {
        $_SESSION["didPaymentFail"] = true;
        header("location: payment.php");
        exit();
    }
}

// If payment details are submitted via POST request from payment.php
if (isset($_POST["card-name"])) {
    validatePaymentMethod();

    // Add payment details to orders table
    $orderDAO = new OrderDAO();
    $orderDAO->updatePaymentMethod($_SESSION["orderNo"], $_SESSION["cardType"], $_SESSION["cardNumber"], $_SESSION["expiryDate"], $_SESSION["cvv"]);

    // Update order status to "Paid"
    $orderDAO->updateOrderStatus($_SESSION["orderNo"], "Paid");

    $_SESSION["isPaid"] = true;
    // Show receipt.php
    header("location: receipt.php");

} else {
    header("location: index.php");
    exit();
}


?>