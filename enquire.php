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
                            pattern="[a-zA-Z ]+" placeholder="John">
                        <span class="error-msg" hidden></span>
                    </div>

                    <div class="form-split">
                        <label for="last-name">Last Name<span class="required-field"> *</span></label>
                        <input type="text" id="last-name" name="last-name" required maxlength="25" pattern="[a-zA-Z ]+"
                            placeholder="Doe">
                    </div>
                </div>

                <!-- Email address input -->
                <label for="email">Email Address<span class="required-field"> *</span></label>
                <input type="email" id="email" name="email" required placeholder="example@domain.com">

                <!-- Phone number input -->
                <label for="phone-no">Phone Number<span class="required-field"> *</span></label>
                <input type="text" id="phone-no" name="phone-no" maxlength="10" pattern="\d{10}"
                    placeholder="i.e. 0123456789" required>

                <!-- Field set for contact method -->
                <fieldset id="contact-mtd">
                    <!-- Make radio buttons and legend to the same row -->

                    <legend class="inline-legend">Contact Method<span class="required-field"> *</span></legend>

                    <div class="inline-beside-legend">
                        <div class="combine-rad">
                            <input type="radio" id="email-rad" name="contact-method" value="email" required>
                            <label for="email-rad">Email</label>
                        </div>
                        <div class="combine-rad">
                            <input type="radio" id="post-rad" name="contact-method" value="post">
                            <label for="post-rad">Post</label>
                        </div>
                        <div class="combine-rad">
                            <input type="radio" id="phone-rad" name="contact-method" value="phone">
                            <label for="phone-rad">Phone</label>
                        </div>
                    </div>

                </fieldset>

                <fieldset>
                    <legend class="block-legend">Shipping Address</legend>

                    <!-- Street address input -->
                    <label for="ship-street-add">Street Address<span class="required-field"> *</span></label>
                    <input type="text" id="ship-street-add" name="ship-street-add" required maxlength="40"
                        pattern="[a-zA-Z0-9 ]+">

                    <!-- Street town input -->
                    <label for="ship-street-suburb">Suburb/Town<span class="required-field"> *</span></label>
                    <input type="text" id="ship-street-suburb" name="ship-street-suburb" required maxlength="20"
                        pattern="[a-zA-Z0-9 ]+">

                    <!-- Field set for address state and postcode -->

                    <!-- Combine state and postcode input into the same row -->
                    <div class="combine-row">
                        <div class="form-split">
                            <label for="ship-street-state">State<span class="required-field"> *</span></label>
                            <select id="ship-street-state" name="ship-street-state" required>
                                <option value="" selected>--- Please Select ---</option>
                                <option value="VIC">VIC</option>
                                <option value="NSW">NSW</option>
                                <option value="QLD">QLD</option>
                                <option value="NT">NT</option>
                                <option value="WA">WA</option>
                                <option value="SA">SA</option>
                                <option value="TAS">TAS</option>
                                <option value="ACT">ACT</option>
                            </select>
                        </div>
                        <div class="form-split" id="ship-post-container">
                            <label for="ship-street-post">Postcode<span class="required-field"> *</span></label>
                            <input type="text" id="ship-street-post" class="has-error-span" name="ship-street-post"
                                required pattern="\d{4}" placeholder="i.e. 3000" maxlength="4">
                            <span class="error-msg" hidden></span>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend class="block-legend">Billing Address</legend>
                    <!-- Check box to use shipping address for billing address -->
                    <div class="combine-rad">
                        <input type="checkbox" id="use-ship-check" name="use-ship-check" value="true">
                        <label for="use-ship-check">Use shipping address</label>
                    </div>


                    <!-- Container to hide billing address when check box is ticked -->
                    <div id="bill-address-container">
                        <!-- Street address input -->
                        <div id="bill-street-container">
                            <label for="bill-street-add">Street Address<span class="required-field"> *</span></label>
                            <input type="text" id="bill-street-add" name="bill-street-add" class="has-error-span"
                                maxlength="40" pattern="[a-zA-Z0-9 ]+">
                            <span class="error-msg" hidden></span>
                        </div>


                        <!-- Street town input -->
                        <div id="bill-suburb-container">
                            <label for="bill-street-suburb">Suburb/Town<span class="required-field"> *</span></label>
                            <input type="text" id="bill-street-suburb" name="bill-street-suburb" class="has-error-span"
                                maxlength="20" pattern="[a-zA-Z0-9 ]+">
                            <span class="error-msg" hidden></span>
                        </div>


                        <!-- Combine state and postcode input into the same row -->
                        <div class="combine-row" id="bill-state-container">
                            <div class="form-split">
                                <label for="bill-street-state">State<span class="required-field"> *</span></label>
                                <select id="bill-street-state" name="bill-street-state" class="has-error-span">
                                    <option value="" selected>--- Please Select ---</option>
                                    <option value="VIC">VIC</option>
                                    <option value="NSW">NSW</option>
                                    <option value="QLD">QLD</option>
                                    <option value="NT">NT</option>
                                    <option value="WA">WA</option>
                                    <option value="SA">SA</option>
                                    <option value="TAS">TAS</option>
                                    <option value="ACT">ACT</option>
                                </select>
                                <span class="error-msg" hidden></span>
                            </div>
                            <div class="form-split" id="bill-post-container">
                                <label for="bill-street-post">Postcode<span class="required-field"> *</span></label>
                                <input type="text" id="bill-street-post" name="bill-street-post" class="has-error-span"
                                    pattern="\d{4}" placeholder="i.e. 3000" maxlength="4">
                                <span class="error-msg" hidden></span>
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
                    <p>A$<span id="final-price">0.00</span></p>
                </div>
            </fieldset>

            <!-- Text area for comment -->
            <label for="comment">Additional Comments</label>
            <textarea id="comment" name="comment" rows="5" cols="40"
                placeholder="Add any special requests or preferences for your purchase..."></textarea>

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