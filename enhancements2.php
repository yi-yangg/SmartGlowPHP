<?php
/**
 * Enhancments 2 page for SmartGlow website detailing the Javascript enhancements made to the website
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file enhancements2.php
 */

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Javascript enhancements applied to the webpage">
    <meta name="keywords" content="Javascript, Enhancement">
    <meta name="author" content="Chong Yi Yang">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhancements 2 | SmartGlow</title>
    <!-- Icon beside title link -->
    <link rel="icon" href="images/smartglow.ico" type="image/x-icon">

    <!-- Link for CSS style sheet -->
    <link rel="stylesheet" href="styles/style.css">
    <script src="scripts/enhancements.js"></script>
</head>

<body>
    <!-- Include header and timer banner -->
    <?php
    include_once("header.inc");
    include_once("timer.inc");
    ?>

    <!-- Enhancement page hero -->
    <section class="enhancement-hero">
        <div class="hero-content">
            <h1>Enhancements 2 Overview</h1>
            <p>This page details the Javascript enhancements made to the SmartGlow website</p>
        </div>
    </section>

    <!-- Enhancement article -->
    <article class="enhancement-article">
        <h2>Enhancement tasks</h2>
        <section>
            <h3 class="faq-question">Enhancement 1: Dynamic Product Management and Error Handling</h3>
            <p>
                This feature allows users to dynamically add or delete products they have selected. Users trigger this
                functionality by clicking the 'Add Product' button in the <a class="inline-link"
                    href="enquire.php#product-list">product section</a> of the enquiry page.
                Upon clicking, a new product entry is created and inserted into the DOM dynamically, allowing users to
                view and manage their selected products.
            </p>
            <p>
                Users can also remove products by clicking on the trash icon next to the product entry. Additionally,
                when a user selects a product from the list, its associated product options (size/length) are
                dynamically generated and added to the DOM.
            </p>
            <p>
                In the enquiry form, error spans are used to display real-time validation feedback, ensuring that the
                user fills out the form correctly. A toast notification system is implemented to give users interactive
                feedback, such as confirming a successful submission or indicating form errors.
            </p>
            <p> To implement this feature: </p>
            <ul>
                <li>"Add Product" button triggers an event listener that dynamically creates and appends new product
                    entries to the DOM using methods like "createElement()" and "appendChild()".</li>
                <li>For removal, the trash icon has an event listener that removes the selected product using
                    "remove()".</li>
                <li>Product options are generated when a product is selected, with an event listener (onchange) that
                    adds the relevant options to the DOM.</li>
                <li>Using the hidden attribute, error messages and toast notification can be hidden from the user and
                    shown when an error has occurred.</li>
            </ul>

            <p>
                For more details on this enhancement, visit the <a class="inline-link"
                    href="enquire.php#product-list">Enquire</a> page.
            </p>
            <p>
                References: <a
                    href="https://developer.mozilla.org/en-US/docs/Web/API/Document_Object_Model/Introduction"
                    class="inline-link">Introduction to the DOM</a> and
                <a href="https://www.w3schools.com/howto/howto_js_snackbar.asp" class="inline-link">W3School Toast
                    Notification</a>
            </p>
        </section>

        <section>
            <h3 class="faq-question">Enhancement 2: Advanced Payment Timer</h3>
            <p>
                The payment timer feature counts down from a specified duration (10 mins), alerting users when time is
                running out. This enhancement is automatically triggered when the user clicks the
                "Pay Now" button on the <a class="inline-link" href="enquire.php">enquiry page</a> and navigates to the
                <a class="inline-link" href="payment.php">payment page</a>. Once the timer
                starts, it remains visible at the top of the page, allowing users to keep track of their remaining time,
                no matter which page they are viewing.
            </p>
            <p>
                To implement this feature:
            </p>
            <ul>
                <li>Once everything has been validated in the enquiry page, get the current time using "new
                    Date().getTime() and store in localStorage"</li>
                <li>Make use of the addEventListener window onload to load the timer when a page is loaded.</li>
                <li>Checks the current time against the stored time and dynamically update the timer using
                    "setInterval()"</li>
                <li>Timer visibility is controlled using Javascript by adding/removing the hidden attribute</li>
            </ul>
            <p>For more information on this enhancement, check out the <a class="inline-link"
                    href="payment.php">Payment Page</a>.</p>
            <p>
                References:
                <a href="https://developer.mozilla.org/en-US/docs/Web/API/setInterval" class="inline-link">setInterval
                    documentation</a> and
                <a href="https://developer.mozilla.org/en-US/docs/Web/API/Window/load_event"
                    class="inline-link">addEventListener documentation</a>
            </p>
        </section>
    </article>
    <!-- Include footer using php -->
    <?php
    include_once("footer.inc");
    ?>
</body>

</html>