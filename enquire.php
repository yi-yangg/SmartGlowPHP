<?php
/**
 * Enquire page for SmartGlow, where users can make purchases on products. Contains a form for users to fill in their personal information, 
 * shipping and billing address, product selection and additional comments. Users can also add multiple products to their purchase.
 * 
 * When the user submits the form, all information is validated in payment.php before being processed. If there are any errors, the user 
 * is redirected back to this page.
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file enquire.php
 */

require_once("utilities.php");
session_start();

// Find the state selected in the session
function findState($fieldName)
{
    if (!isset($_SESSION[$fieldName])) {
        return "";
    } else if (isset($_SESSION["errors"][$fieldName]) && !empty($_SESSION["errors"][$fieldName])) {
        // If error array is set in the session and the current field has an error, return empty string
        return "";
    } else {
        return $_SESSION[$fieldName];
    }
}

function getSelectedProduct($productNo, $index) {

    if (!isset($_SESSION["product-" . $productNo])) {
        return "";
    } else if (isset($_SESSION["productErrors"]) && !empty($_SESSION["productErrors"][$index])) {
        // If product error array is set in the session and the current product has an error, return empty string
        return "";
    } else {
        return $_SESSION["product-" . $productNo];
    }
}

function getSelectedOption($productNo, $index) {

    if (!isset($_SESSION["option-" . $productNo])) {
        return "";
    } else if (isset($_SESSION["optionErrors"]) && !empty($_SESSION["optionErrors"][$index])) {
        // If option error array is set in the session and the current option has an error, return empty string
        return "";
    } else {
        return $_SESSION["option-" . $productNo];
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description"
        content="Reach out with SmartGlow for any questions or enquiries about our smart lighting products.">
    <meta name="keywords" content="SmartGlow enquiry, product enquiry, customer support, contact us">
    <meta name="author" content="Chong Yi Yang">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquire Us | SmartGlow</title>
    <!-- Icon beside title link -->
    <link rel="icon" href="images/smartglow.ico" type="image/x-icon">

    <!-- Link for CSS style sheet -->
    <link rel="stylesheet" href="styles/style.css">
    <script src="scripts/part2.js"></script>
    <script src="scripts/enhancements.js"></script>
    <script src="scripts/part2_enquire.js"></script>
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



    <!-- Hero for enquire page -->
    <section class="hero-enquire">
        <div class="hero-content">
            <h1>Got Questions?</h1>
            <p>Check out our FAQ for quick answers.</p>
            <p>Still need help? Use the form below.</p>
        </div>
    </section>

    <!-- FAQ section before the enquiry form -->
    <section class="faq">
        <h2>Frequently Asked Questions</h2>

        <h3 class="faq-question">What products do you offer?</h3>
        <p>We offer a range of smart lighting products, such as smart light bulbs, smart LEDs, and smart flexible light
            strips.</p>

        <h3 class="faq-question">Do you offer any installation services?</h3>
        <p>Currently, we do not offer installation services. However, our products are designed for easy installation by
            the user.</p>

        <h3 class="faq-question">How can I control my lights?</h3>
        <p>There are multiple ways you can control our lights, such as through our smartphone app or the provided remote
            control.</p>

        <h3 class="faq-question">How can I request a refund?</h3>
        <!-- Paragraph with link and mailto to a sample support email -->
        <p>
            If you are not satisfied with your purchase, you can request a refund by contacting our
            <a class="inline-link" href="mailto:support@smartglow.com">support team</a>. Please also provide
            your order number and reason for the refund in your request.
        </p>
    </section>

    <!-- Section for enquiry form -->
    <section id="enquire-sec" class="enquire-form">
        <!-- Content before the form -->
        <h2>Ready to Purchase?</h2>
        <p>Thank you for choosing us! Please fill out the form below to complete your purchase.</p>

        <!-- Form -->
        <form action="payment.php" id="purchase-form" method="post" novalidate="novalidate">
            <!-- Field set for personal information -->
            <fieldset>
                <legend class="block-legend">Personal Information</legend>

                <!-- Div to combine multiple rows using flex box -->
                <div class="combine-row">

                    <!-- Div to combine label and input so that it acts as a block element -->
                    <div class="form-split">
                        <label for="first-name">First Name<span class="required-field"> *</span></label>
                        <input type="text" id="first-name" name="first-name" required maxlength="25"
                            pattern="[a-zA-Z ]+" placeholder="John" <?php
                            checkFieldErrors("errors", "firstName");
                            ?>>
                        <span class="error-msg">
                            <?php
                            displayErrorIfExist("errors", "firstName");

                            ?>
                        </span>
                    </div>

                    <div class="form-split">
                        <label for="last-name">Last Name<span class="required-field"> *</span></label>
                        <input type="text" id="last-name" name="last-name" required maxlength="25" pattern="[a-zA-Z ]+"
                            placeholder="Doe" <?php
                            checkFieldErrors("errors", "lastName");
                            ?>>
                        <span class="error-msg">
                            <?php
                            displayErrorIfExist("errors", "lastName");
                            ?>
                        </span>
                    </div>
                </div>

                <!-- Email address input -->
                <label for="email">Email Address<span class="required-field"> *</span></label>
                <input type="email" id="email" name="email" required placeholder="example@domain.com" <?php
                checkFieldErrors("errors", "email");
                ?>>
                <span class="error-msg">
                    <?php
                    displayErrorIfExist("errors", "email");
                    ?>
                </span>
                <!-- Phone number input -->
                <label for="phone-no">Phone Number<span class="required-field"> *</span></label>
                <input type="text" id="phone-no" name="phone-no" maxlength="10" pattern="\d{10}"
                    placeholder="i.e. 0123456789" required <?php
                    checkFieldErrors("errors", "phoneNo");
                    ?>>
                <span class="error-msg">
                    <?php
                    displayErrorIfExist("errors", "phoneNo");
                    ?>
                </span>

                <!-- Field set for contact method -->
                <fieldset id="contact-mtd">
                    <!-- Make radio buttons and legend to the same row -->

                    <legend class="inline-legend">Contact Method<span class="required-field"> *</span></legend>

                    <div class="inline-beside-legend">
                        <div class="combine-rad">
                            <input type="radio" id="email-rad" name="contact-method" value="email" required <?php
                            echo (isset($_SESSION["contactMethod"]) && $_SESSION["contactMethod"] == "email") ? "checked" : "";
                            ?>>
                            <label for="email-rad">Email</label>
                        </div>
                        <div class="combine-rad">
                            <input type="radio" id="post-rad" name="contact-method" value="post" <?php
                            echo (isset($_SESSION["contactMethod"]) && $_SESSION["contactMethod"] == "post") ? "checked" : "";
                            ?>>
                            <label for="post-rad">Post</label>
                        </div>
                        <div class="combine-rad">
                            <input type="radio" id="phone-rad" name="contact-method" value="phone" <?php
                            echo (isset($_SESSION["contactMethod"]) && $_SESSION["contactMethod"] == "phone") ? "checked" : "";
                            ?>>
                            <label for="phone-rad">Phone</label>
                        </div>
                    </div>
                    <span class="error-msg">
                        <?php
                        displayErrorIfExist("errors", "contactMethod");
                        ?>
                    </span>

                </fieldset>

                <fieldset>
                    <legend class="block-legend">Shipping Address</legend>

                    <!-- Street address input -->
                    <label for="ship-street-add">Street Address<span class="required-field"> *</span></label>
                    <input type="text" id="ship-street-add" name="ship-street-add" required maxlength="40"
                        pattern="[a-zA-Z0-9 ]+" <?php
                        checkFieldErrors("errors", "shipStreet");
                        ?>>
                    <span class="error-msg">
                        <?php
                        displayErrorIfExist("errors", "shipStreet");
                        ?>
                    </span>

                    <!-- Street town input -->
                    <label for="ship-street-suburb">Suburb/Town<span class="required-field"> *</span></label>
                    <input type="text" id="ship-street-suburb" name="ship-street-suburb" required maxlength="20"
                        pattern="[a-zA-Z0-9 ]+" <?php
                        checkFieldErrors("errors", "shipSuburb");
                        ?>>
                    <span class="error-msg">
                        <?php
                        displayErrorIfExist("errors", "shipSuburb");
                        ?>
                    </span>

                    <!-- Combine state and postcode input into the same row -->
                    <div class="combine-row">
                        <div class="form-split">
                            <label for="ship-street-state">State<span class="required-field"> *</span></label>
                            <select id="ship-street-state" name="ship-street-state" required <?php
                            if (isset($_SESSION["errors"]["shipState"]) && !empty($_SESSION["errors"]["shipState"])) {
                                echo ' class="has-error"';
                            }
                            ?>>
                                <option value="" <?php echo findState("shipState") == "" ? "selected" : "" ?>>--- Please
                                    Select ---
                                </option>
                                <option value="VIC" <?php echo findState("shipState") == "VIC" ? "selected" : "" ?>>VIC
                                </option>
                                <option value="NSW" <?php echo findState("shipState") == "NSW" ? "selected" : "" ?>>NSW
                                </option>
                                <option value="QLD" <?php echo findState("shipState") == "QLD" ? "selected" : "" ?>>QLD
                                </option>
                                <option value="NT" <?php echo findState("shipState") == "NT" ? "selected" : "" ?>>NT
                                </option>
                                <option value="WA" <?php echo findState("shipState") == "WA" ? "selected" : "" ?>>WA
                                </option>
                                <option value="SA" <?php echo findState("shipState") == "SA" ? "selected" : "" ?>>SA
                                </option>
                                <option value="TAS" <?php echo findState("shipState") == "TAS" ? "selected" : "" ?>>TAS
                                </option>
                                <option value="ACT" <?php echo findState("shipState") == "ACT" ? "selected" : "" ?>>ACT
                                </option>
                            </select>
                            <span class="error-msg">
                                <?php
                                displayErrorIfExist("errors", "shipState");
                                ?>
                            </span>
                        </div>
                        <div class="form-split" id="ship-post-container">
                            <label for="ship-street-post">Postcode<span class="required-field"> *</span></label>
                            <input type="text" id="ship-street-post" name="ship-street-post" required pattern="\d{4}"
                                placeholder="i.e. 3000" maxlength="4" <?php
                                checkFieldErrors("errors", "shipPost");
                                ?>>
                            <span class="error-msg">
                                <?php
                                displayErrorIfExist("errors", "shipPost");
                                ?>
                            </span>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend class="block-legend">Billing Address</legend>
                    <!-- Check box to use shipping address for billing address -->
                    <div class="combine-rad">
                        <input type="checkbox" id="use-ship-check" name="use-ship-check" value="true" <?php if (isset($_SESSION["useShipCheck"]) && $_SESSION["useShipCheck"]) {
                            echo "checked";
                        } ?>>
                        <label for="use-ship-check">Use shipping address</label>
                    </div>


                    <!-- Container to hide billing address when check box is ticked -->
                    <div id="bill-address-container">
                        <!-- Street address input -->
                        <div id="bill-street-container">
                            <label for="bill-street-add">Street Address<span class="required-field"> *</span></label>
                            <input type="text" id="bill-street-add" name="bill-street-add" maxlength="40"
                                pattern="[a-zA-Z0-9 ]+" <?php
                                checkFieldErrors("errors", "billStreet");
                                ?>>
                            <span class="error-msg">
                                <?php
                                displayErrorIfExist("errors", "billStreet");
                                ?>
                            </span>
                        </div>


                        <!-- Street town input -->
                        <div id="bill-suburb-container">
                            <label for="bill-street-suburb">Suburb/Town<span class="required-field"> *</span></label>
                            <input type="text" id="bill-street-suburb" name="bill-street-suburb" maxlength="20"
                                pattern="[a-zA-Z0-9 ]+" <?php
                                checkFieldErrors("errors", "billSuburb");
                                ?>>
                            <span class="error-msg">
                                <?php
                                displayErrorIfExist("errors", "billSuburb");
                                ?>
                            </span>
                        </div>


                        <!-- Combine state and postcode input into the same row -->
                        <div class="combine-row" id="bill-state-container">
                            <div class="form-split">
                                <label for="bill-street-state">State<span class="required-field"> *</span></label>
                                <select id="bill-street-state" name="bill-street-state" <?php
                                if (isset($_SESSION["errors"]["billState"]) && !empty($_SESSION["errors"]["billState"])) {
                                    echo ' class="has-error"';
                                }
                                ?>>
                                    <option value="" <?php echo findState("billState") == "" ? "selected" : "" ?>>---
                                        Please Select ---</option>
                                    <option value="VIC" <?php echo findState("billState") == "VIC" ? "selected" : "" ?>>
                                        VIC</option>
                                    <option value="NSW" <?php echo findState("billState") == "NSW" ? "selected" : "" ?>>
                                        NSW</option>
                                    <option value="QLD" <?php echo findState("billState") == "QLD" ? "selected" : "" ?>>
                                        QLD</option>
                                    <option value="NT" <?php echo findState("billState") == "NT" ? "selected" : "" ?>>NT
                                    </option>
                                    <option value="WA" <?php echo findState("billState") == "WA" ? "selected" : "" ?>>WA
                                    </option>
                                    <option value="SA" <?php echo findState("billState") == "SA" ? "selected" : "" ?>>SA
                                    </option>
                                    <option value="TAS" <?php echo findState("billState") == "TAS" ? "selected" : "" ?>>
                                        TAS</option>
                                    <option value="ACT" <?php echo findState("billState") == "ACT" ? "selected" : "" ?>>
                                        ACT</option>
                                </select>
                                <span class="error-msg">
                                    <?php
                                    displayErrorIfExist("errors", "billState");
                                    ?>
                                </span>
                            </div>
                            <div class="form-split" id="bill-post-container">
                                <label for="bill-street-post">Postcode<span class="required-field"> *</span></label>
                                <input type="text" id="bill-street-post" name="bill-street-post" pattern="\d{4}"
                                    placeholder="i.e. 3000" maxlength="4" <?php
                                    checkFieldErrors("errors", "billPost");
                                    ?>>
                                <span class="error-msg">
                                    <?php
                                    displayErrorIfExist("errors", "billPost");
                                    ?>
                                </span>
                            </div>

                        </div>
                    </div>

                </fieldset>


            </fieldset>

            <!-- Field set for product information -->
            <fieldset>
                <legend class="block-legend">Product Selection<span class="required-field"> *</span></legend>
                <!-- List of products -->
                <div id="product-list">
                    <?php 

                    $totalPrice = 0;
                    // Check if product number array is set in the session
                    if (!isset($_SESSION["productNoArr"])) {
                        // If it is not set, generate basic product item
                        echo '
                        <div class="product-item" id="prod-item-1">
                            <div class="prod-head-group">
                                <label for="product-1">Select a Product</label>
                                <button type="button" class="delete-prod" id="del-prod-1">
                                    <span>Delete</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="32" height="32" viewBox="0 0 32 32">
                                        <path d="M 15 4 C 14.476563 4 13.941406 4.183594 13.5625 4.5625 C 13.183594 4.941406 13 5.476563 13 6 L 13 7 L 7 7 L 7 9 L 8 9 L 8 25 C 8 26.644531 9.355469 28 11 28 L 23 28 C 24.644531 28 26 26.644531 26 25 L 26 9 L 27 9 L 27 7 L 21 7 L 21 6 C 21 5.476563 20.816406 4.941406 20.4375 4.5625 C 20.058594 4.183594 19.523438 4 19 4 Z M 15 6 L 19 6 L 19 7 L 15 7 Z M 10 9 L 24 9 L 24 25 C 24 25.554688 23.554688 26 23 26 L 11 26 C 10.445313 26 10 25.554688 10 25 Z M 12 12 L 12 23 L 14 23 L 14 12 Z M 16 12 L 16 23 L 18 23 L 18 12 Z M 20 12 L 20 23 L 22 23 L 22 12 Z"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <select id="product-1" name="product-1" class="has-error-span" required>';
                        
                        $productDAO = new ProductDAO();
                        $allProducts = $productDAO->getProducts();
                        
                        // Add the selected product value if it is set in the session
                        echo '
                            <option value="" selected>--- Please Select ---</option>';

                        foreach($allProducts as $product) {
                            echo '<option value="' . strtolower($product) . '">' . $product . '</option>';
        
                        }
                        echo '</select>

                            <div id="product-options-1"></div>  
                        </div>';
                    } else {
                        
                        $i = 0;
                        // If it is set, loop through the array and generate product items, options and footer
                        foreach($_SESSION["productNoArr"] as $productNo) {
                            echo '
                            <div class="product-item" id="prod-item-' . $productNo . '">
                                <div class="prod-head-group">
                                    <label for="product-' . $productNo . '">Select a Product</label>
                                    <button type="button" class="delete-prod" id="del-prod-' . $productNo . '">
                                        <span>Delete</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="32" height="32" viewBox="0 0 32 32">
                                            <path d="M 15 4 C 14.476563 4 13.941406 4.183594 13.5625 4.5625 C 13.183594 4.941406 13 5.476563 13 6 L 13 7 L 7 7 L 7 9 L 8 9 L 8 25 C 8 26.644531 9.355469 28 11 28 L 23 28 C 24.644531 28 26 26.644531 26 25 L 26 9 L 27 9 L 27 7 L 21 7 L 21 6 C 21 5.476563 20.816406 4.941406 20.4375 4.5625 C 20.058594 4.183594 19.523438 4 19 4 Z M 15 6 L 19 6 L 19 7 L 15 7 Z M 10 9 L 24 9 L 24 25 C 24 25.554688 23.554688 26 23 26 L 11 26 C 10.445313 26 10 25.554688 10 25 Z M 12 12 L 12 23 L 14 23 L 14 12 Z M 16 12 L 16 23 L 18 23 L 18 12 Z M 20 12 L 20 23 L 22 23 L 22 12 Z"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <select id="product-' . $productNo . '" name="product-' . $productNo . '" required';
                            // If product errors array is set in the session and the current product has an error, add has-error class
                            if (isset($_SESSION["productErrors"]) && !empty($_SESSION["productErrors"][$i])) {
                                echo ' class="has-error"';
                            }
                            echo'>';  

                            // Populate select product values from mysql
                            $productDAO = new ProductDAO();
                            $allProducts = $productDAO->getProducts();
                            
                            
                            $selectedProduct = getSelectedProduct($productNo, $i);
                            // Add the selected product value if it is set in the session
                            echo '
                                <option value="" ';
                                echo $selectedProduct == "" ? 'selected' : '';
                                echo '>--- Please Select ---</option>';

                            foreach($allProducts as $product) {
                                echo '<option value="' . strtolower($product) . '" ';
                                echo strtolower($selectedProduct) == strtolower($product) ? 'selected' : '';
                                echo '>' . $product . '</option>';
            
                            }
                            echo '
                            </select>

                            <span class="error-msg">';
                            echo isset($_SESSION["productErrors"]) ? $_SESSION["productErrors"][$i] : ''; 
                            echo '</span> 
                            <div id="product-options-' . $productNo .'">';
                            
                            $selectedOption = getSelectedOption($productNo, $i);
                            // Check if the selected product is not empty and the option array is set in the session
                            if (!empty($selectedProduct) && isset($_SESSION["option-" . $productNo])) {
                                $optionDAO = new ProductOptionDAO();

                                $allOptions = $optionDAO->getProductOptions($selectedProduct);
                                
                                echo '<label class="prod-option-header">Options:</label>';
                                echo '<input type=hidden id="option-' . $productNo . '" name="option-' . $productNo . '" value="';
                                echo $selectedOption;
                                echo '">';

                                foreach($allOptions as $option) {
                                    echo '<button type="button" class="option-btn';
                                    
                                    if (strtolower($selectedOption) == strtolower($option["option_name"])) {
                                        echo ' active';
                                    }
                                    echo '" data-value="';
                                    echo strtolower($option["option_name"]) . '" data-price="' . $option["price"] . '"';
                                    echo '>' . $option["option_name"] . '<br>A$' . $option["price"] . '</button> ';
                                }

                                echo '<span class="error-msg">';
                                echo isset($_SESSION["optionErrors"]) ? $_SESSION["optionErrors"][$i] : ''; 
                                echo '</span></div>';
                            }
                            
                            // Check if product footer is set in the session
                            if (!empty($selectedOption) && isset($_SESSION["quantity-" . $productNo])) {
                                echo '<div class="product-footer">
                                    <div id="qty-container-'. $productNo . '">
                                        <label for="quantity-'. $productNo . '" class="prod-option-header">Quantity:</label>
                                        <div>
                                            <button type="button" class="qty-btn" id="minus-qty-'. $productNo . '">
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 48 48">
                                                    <path d="M 6.5 22.5 A 1.50015 1.50015 0 1 0 6.5 25.5 L 41.5 25.5 A 1.50015 1.50015 0 1 0 41.5 22.5 L 6.5 22.5 z"></path>
                                                </svg>
                                            </button>
                                            <input type="text" id="quantity-'. $productNo . '" name="quantity-'. $productNo . '" value="' . (empty($_SESSION["quantity-" . $productNo]) ? 1 : $_SESSION["quantity-" . $productNo]) . '" class="qty-input';
                                     
                                echo isset($_SESSION["quantityErrors"]) && !empty($_SESSION["quantityErrors"][$i]) ? ' has-error' : '';
                                echo '">
                                            <button type="button" class="qty-btn" id="add-qty-'. $productNo . '">
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 48 48">
                                                    <path d="M 23.976562 4.9785156 A 1.50015 1.50015 0 0 0 22.5 6.5 L 22.5 22.5 L 6.5 22.5 A 1.50015 1.50015 0 1 0 6.5 25.5 L 22.5 25.5 L 22.5 41.5 A 1.50015 1.50015 0 1 0 25.5 41.5 L 25.5 25.5 L 41.5 25.5 A 1.50015 1.50015 0 1 0 41.5 22.5 L 25.5 22.5 L 25.5 6.5 A 1.50015 1.50015 0 0 0 23.976562 4.9785156 z"></path>
                                                </svg>
                                            </button>
                                        </div>';
                                        
                                echo '<span class="error-msg">';
                                echo isset($_SESSION["quantityErrors"]) ? $_SESSION["quantityErrors"][$i] : '';
                                echo '</span>';

                                echo '</div>
                                    
                                    <div class="price-container">
                                        <h4>Total:</h4>
                                        <p>A$<span id="price-'. $productNo . '">';
                                // Calculate the total price of the product
                                $optionPrice = $optionDAO->getOptionPrice($selectedProduct, $selectedOption);
                                if (isset($_SESSION["quantityErrors"]) && !empty($_SESSION["quantityErrors"][$i])) {
                                    $quantity = 0;
                                } else {
                                    $quantity = $_SESSION["quantity-" . $productNo];
                                }
                                // Calculate the total price of the product
                                $itemPrice = number_format($optionPrice * $quantity, 2);
                                
                                // Add the total price to the final price
                                $totalPrice += $itemPrice;
                                echo $itemPrice;

                                echo '</span></p>
                                    </div>';

                            }
                            echo '</div></div>';

                            $i++;
                        }
                    }

                    ?>
                </div>


                <!-- Add button to add products -->
                <button type="button" id="new-product">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 48 48">
                        <path
                            d="M 23.976562 4.9785156 A 1.50015 1.50015 0 0 0 22.5 6.5 L 22.5 22.5 L 6.5 22.5 A 1.50015 1.50015 0 1 0 6.5 25.5 L 22.5 25.5 L 22.5 41.5 A 1.50015 1.50015 0 1 0 25.5 41.5 L 25.5 25.5 L 41.5 25.5 A 1.50015 1.50015 0 1 0 41.5 22.5 L 25.5 22.5 L 25.5 6.5 A 1.50015 1.50015 0 0 0 23.976562 4.9785156 z">
                        </path>
                    </svg>
                    <span>New Product</span>
                </button>
                <!-- Final price container -->
                <div class="final-price-container">
                    <h4>Final Price</h4>
                    <p>A$<span id="final-price"><?php echo number_format($totalPrice, 2) ?></span></p>
                </div>
            </fieldset>

            <!-- Text area for comment -->
            <label for="comment">Additional Comments</label>
            <textarea id="comment" name="comment" rows="5" cols="40"
                placeholder="Add any special requests or preferences for your purchase..."><?php echo (isset($_SESSION["comment"]) ? $_SESSION["comment"] : "") ?></textarea>

            <!-- Div to group submit and reset button together -->
            <div class="form-act">
                <input type="submit" value="Pay Now">
                <input type="reset" value="Reset form">
            </div>

        </form>
    </section>
    <!-- Include footer using php -->
    <?php
    include_once("footer.inc");
    ?>
</body>

</html>