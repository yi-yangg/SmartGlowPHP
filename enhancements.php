<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Advanced HTML, CSS and Responsive Design methods applied to SmartGlow's Webpage">
    <meta name="keywords" content="HTML, CSS, Responsive Design, Enhancement">
    <meta name="author" content="Chong Yi Yang">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhancements | SmartGlow</title>
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
            <h1>Enhancements Overview</h1>
            <p>This page details the enhancements made to the SmartGlow website, demonstrating the use of advanced HTML
                elements, CSS properties and responsive designs that go beyond the basic requirements.</p>
        </div>
    </section>

    <!-- Enhancement article -->
    <article class="enhancement-article">
        <h2>Enhancement tasks</h2>
        <!-- Sections for each enhancement -->
        <section>
            <h3 class="faq-question">Image Map for Interactive Product Exploration &#40;HTML&#41;</h3>
            <p>The <a class="inline-link" href="index.html#explore-section">product exploration section</a> on the
                product page uses an image map to create an interactive experience. This allows users to hover over and
                click different areas of an image to learn more about the specific products. The image map was created
                using the &lt;map&gt; and &lt;area&gt; elements.</p>
        </section>

        <section>
            <h3 class="faq-question">Advanced CSS Properties</h3>

            <h4>a&#41; ::before and ::after Pseudo-elements:</h4>
            <p>
                The <strong>::before</strong> pseudo-element was used in several hero sections of the code:
                <a class="inline-link" href="index.html#home-hero-sec">Home Hero</a>, <a class="inline-link"
                    href="product.html#product-hero-sec">Product Hero</a>, and <a class="inline-link"
                    href="about.html#about-hero-sec">About Hero</a>
                It is used to darken the hero background image while maintaining the clarity of headings and paragraphs.
                Information from: <a class="inline-link"
                    href="https://stackoverflow.com/questions/49644492/darkening-background-image-with-before-css3-pseudo-selector">Stack
                    Overflow</a>
            </p>

            <p>
                The <strong>::after</strong> pseudo-element is also used in several sections: <a class="inline-link"
                    href="about.html#details-sec">My Details Section</a> and <a class="inline-link"
                    href="product.html#smart-bulb-sec">Product Section</a> to clear floats, ensuring that floating
                elements do not exceed or overlap with their sections.
                Information from: <a class="inline-link"
                    href="https://stackoverflow.com/questions/10699343/using-after-to-clear-floating-elements">Stack
                    Overflow</a>
            </p>

            <h4>b&#41; :hover and :focus Pseudo-classes</h4>
            <p>
                The <strong>:hover</strong> pseudo-class was used to add animations to HTML elements, enhancing
                interactivity and user experience. This pseudo-class is applied to multiple elements: <a
                    class="inline-link" href="index.html#feature-sec">Feature Cards</a>, <a class="inline-link"
                    href="index.html">Home page buttons</a>, and Navigation Link on the header.
            </p>

            <p>
                The <strong>::focus</strong> pseudo-class was also used to add animations to forms: <a
                    class="inline-link" href="enquire.html#enquire-sec">Fields in Enquiry Forms</a>.
            </p>

            <h4 id="flex-box-usage">c&#41; Usage of flex boxes</h4>
            <p>
                Flexbox is used extensively to create responsive layouts and align elements across various pages of the
                website. The primary uses include:
            </p>

            <ul>
                <li>
                    <strong>Responsive Navigation Bar:</strong> The header navigation bar utilizes flexbox to structure
                    and align menu items.
                </li>
                <li>
                    <strong>Feature Sections:</strong> The <a class="inline-link" href="index.html#feature-sec">Feature
                        Cards</a> on the homepage are arranged using flexbox to ensure that the cards adjust gracefully
                    by using flex-wrap when the viewport size changes.
                </li>
                <li>
                    <strong>Layout of the Explore Section:</strong> In the <a class="inline-link"
                        href="index.html#explore-section">Explore Section</a>, flexbox is utilized to position the
                    content and image side by side.
                </li>
            </ul>

            <p>
                More information: <a class="inline-link"
                    href="https://www.youtube.com/watch?v=phWxA89Dy94&t=97s">Flexbox tutorial video</a>
            </p>
        </section>

        <section>
            <h3 class="faq-question">Responsive Design</h3>
            <p>To ensure the website is responsive, the following techniques were implemented:</p>
            <ul>
                <li>The <code>&lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;</code> tag
                    was added to the head of each page to ensure proper scaling on mobile devices.</li>
                <li>Media queries were used to adjust the layout at different screen sizes: max-width of 950px for the
                    <a class="inline-link" href="product.html">product range page</a> to change the side bar to top bar
                    and max-width of 768px for tablet size screens for all pages, i.e. Navigation bar for header
                    changes, font-size changes, margin and padding changes, etc.
                </li>
                <li>Flexboxes were extensively used to create flexible and responsive layouts, see above <a
                        class="inline-link" href="#flex-box-usage">Usage of flex boxes</a>.</li>
            </ul>
        </section>

    </article>

    <!-- Include footer using php -->
    <?php
    include_once("footer.inc");
    ?>
</body>

</html>