<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Discover the variety of SmartGlow's Product Range">
    <meta name="keywords" content="smart light bulbs, LED, light strips">
    <meta name="author" content="Chong Yi Yang">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Range | SmartGlow</title>
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

    <!-- Hero for product page -->
    <section id="product-hero-sec" class="product-hero">
        <div class="hero-content">
            <h1>Illuminate Your Smart Life with SmartGlow </h1>
            <p>Discover the perfect lighting solutions with our range of smart lighting products</p>
            <p class="copyright-content">Image taken from: <a
                    href="https://tala.co.uk/blogs/journal/dim-to-warm-lighting-explained">https://tala.co.uk/blogs/journal/dim-to-warm-lighting-explained</a>
            </p>
        </div>
    </section>

    <!-- Aside for side navigation bar -->
    <aside class="side-nav">
        <h3>Navigation</h3>
        <ol>
            <li><a href="#smart-light-bulb">Smart Light Bulb</a>
                <ul>
                    <li><a href="#smart-light-bulb-features">Features</a></li>
                    <li><a href="#smart-light-bulb-options">Options</a></li>
                </ul>
            </li>
            <li><a href="#smart-led-strips">Smart LED Strips</a>
                <ul>
                    <li><a href="#smart-led-strips-features">Features</a></li>
                    <li><a href="#smart-led-strips-options">Options</a></li>
                </ul>

            </li>

            <li><a href="#smart-flexi-light">Smart Flexi Light</a>
                <ul>
                    <li><a href="#smart-flexi-light-features">Features</a></li>
                    <li><a href="#smart-flexi-light-options">Options</a></li>
                </ul>
            </li>
        </ol>
    </aside>

    <!-- Section for smart light bulb -->
    <section id="smart-bulb-sec" class="product-section">

        <img class="right-float-img" src="images/smart_light_bulb.png" alt="Smart Light Bulb">

        <h2 id="smart-light-bulb">Smart Light Bulb</h2>
        <p>Our SmartGlow Smart Light Bulb is designed to revolutionize your lighting experience. With its advanced
            features, this smart bulb allows you to control your lighting from anywhere using your smartphone or voice
            commands. Choose from over 16 million colors and various brightness levels to create the perfect ambiance
            for any occasion.</p>
        <h4 id="smart-light-bulb-features">Key Features:</h4>
        <ul>
            <li>Remote Control: Adjust settings from your phone, the provided remote control or voice commands.</li>
            <li>Voice Integration: Compatible with Alexa and Google Assistant.</li>
            <li>Scheduling: Set timers and routines to automate your lighting.</li>
            <li>Energy Efficient: Reduces power consumption with LED technology.</li>
        </ul>

        <h4 id="smart-light-bulb-options">Options:</h4>
        <ul>
            <li>Color Temperature: Choose between warm white, cool white, or daylight.</li>
            <li>Adjustable Brightness: Adjust brightness to suit your needs.</li>
            <li>Different Sizes: Available in A19, BR30, and GU10 formats.</li>
        </ul>
    </section>

    <!-- Section for smart LED Strips -->
    <section class="product-section">
        <img class="left-float-img" src="images/smart_led.png" alt="Smart LED Strips">
        <h2 id="smart-led-strips">Smart LED Strips</h2>
        <p>Our SmartGlow LED Strips offer versatile and dynamic lighting solutions that can transform any space with
            ease. These strips are designed to be flexible and customizable, allowing you to create the perfect
            atmosphere in any room.</p>
        <h4 id="smart-led-strips-features">Key Features:</h4>
        <ul>
            <li>Remote Control: Adjust settings from your phone, the provided remote control or voice commands.</li>
            <li>Energy Efficient: Helps you save on electricity while enjoying vibrant lighting.</li>
            <li>Customizable Length: Can be cut to fit any length to suit your needs.</li>
            <li>Easy Installation: Adhesive backing ensures easy installation on various surfaces.</li>
        </ul>

        <h4 id="smart-led-strips-options">Options:</h4>
        <ul>
            <li>Color Modes: Choose between single color, multi-color, or rotating color-changing modes.</li>
            <li>Two Options: Indoor and outdoor versions are available.</li>
            <li>Different Lengths: Available in 2m, 5m, and 10m lengths.</li>
        </ul>

    </section>

    <!-- Section for smart flex light -->
    <section class="product-section">

        <img class="right-float-img" src="images/smart_flex_light.png" alt="Smart Flexi Light">

        <h2 id="smart-flexi-light">Smart Flexi Light</h2>
        <p>The Smart Flexi Light is an innovative lighting solution designed to adapt to different spaces. With its
            flexible design, it allows this smart light to be bent, shaped and wrapped around various objects. The Smart
            Flexi Light is perfect for highlighting features and adding touch of ambiance to any room.</p>
        <h4 id="smart-flexi-light-features">Key Features:</h4>
        <ul>
            <li>Remote Control: Adjust settings from your phone, the provided remote control or voice commands.</li>
            <li>Energy Efficient: Utilizes LED technology for reduced power consumption.</li>
            <li>Flexible Design: Easily mold and shape the light to fit any space.</li>
            <li>Multi-purpose: Suitable for both indoor and outdoor use.</li>
        </ul>

        <h4 id="smart-flexi-light-options">Options:</h4>
        <ul>
            <li>Color Options: Choose from RGB, warm white, or cool white.</li>
            <li>Mounting Accessories: Includes clips and adhesive backing for easy installation.</li>
            <li>Different Lengths: Available in 1m, 2m, and 5m lengths.</li>
        </ul>
    </section>

    <!-- Include footer using php -->
    <?php
    include_once("footer.inc");
    ?>

</body>

</html>