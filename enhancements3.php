<?php
/**
 * Enhancments 3 page for SmartGlow website detailing the PHP and MySQL enhancements made to the website
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file enhancements3.php
 */

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="PHP and MySQL enhancement made to the SmartGlow website">
    <meta name="keywords" content="PHP, MySQL, SmartGlow, Enhancement">
    <meta name="author" content="Chong Yi Yang">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhancements 3 | SmartGlow</title>
    <!-- Icon beside title link -->
    <link rel="icon" href="images/smartglow.ico" type="image/x-icon">

    <!-- Link for CSS style sheet -->
    <link rel="stylesheet" href="styles/style.css">
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
            <h1>Enhancements 3 Overview</h1>
            <p>This page details the PHP and MySQL enhancements made to the SmartGlow website</p>
        </div>
    </section>

    <!-- Enhancement article -->
    <article class="enhancement-article">
        <h2>Enhancement tasks</h2>
        <section>
            <h3 class=" faq-question">Enhancement 1: User Authentication System</h3>
            <p>
                A robust user authentication system has been implemented, allowing for separate registration, login, and
                logout functionalities for both managers and users. This enhancement creates a clear separation of
                access,
                which ensures only authorized managers can access the <a class="inline-link"
                    href="manager.php">manager.php</a> page. This system
                helps improve security by preventing unauthorized access to sensitive orders information.
            </p>
            <p>
                To implement this feature, the following steps were taken:
            </p>
            <ul>
                <li>Created a <a class="inline-link" href="signup.php">registration page</a> to allow new users to
                    sign up
                    with roles.</li>
                <li>Developed a <a class="inline-link" href="login.php">login page</a> that checks user credentials
                    against the database.</li>
                <li>Utilized session management in PHP to handle user sessions and enforce access restrictions on
                    <a class="inline-link" href="manager.php">manager.php</a>.
                </li>
            </ul>
            <p>
                For further understanding of PHP session management, I referred to <a class="inline-link"
                    href="https://www.w3schools.com/php/php_sessions.asp">W3Schools PHP Sessions</a> and
                <a class="inline-link" href="https://www.tutorialspoint.com/php/php_login_example.htm">TutorialsPoint
                    Login Form</a>.
            </p>
        </section>

        <section>
            <h3 class="faq-question">Enhancement 2: Data Access Object (DAO) Pattern</h3>
            <p>
                The creation of a Data Access Object (DAO) for each database table has been introduced, utilizing PHP
                classes. This enhancement significantly streamlines the database interaction process by encapsulating
                all database access logic within the DAO classes, which eliminates the need to repeatedly create and
                unset the connection variable. As a result, the code becomes more maintainable, organized, and
                efficient. The DAO PHP classes are defined in the <a class="inline-link"
                    href="utilities.php">utilities.php</a> file.
            </p>
            <p>
                To implement this enhancement, the following steps were executed:
            </p>
            <ul>
                <li>Designed the DAO base class &#40;See <a class="inline-link"
                        href="utilities.php">utilities.php</a>&#41;, which is used for the initial connection to the
                    MySQL database.</li>
                <li>Designed individual DAO classes for each table which extends from the DAO base class, and
                    encapsulating
                    CRUD operations within these classes.
                </li>
                <li>Creation of the table if not exist is also queried within the
                    constructor of the DAO class, ensuring that the table is created when the DAO class is instantiated.
                </li>
                <li>Defined methods within each DAO class to handle database interactions, such as
                    insertUser(), getUser(), updateUser() and deleteUser(), each representing a CRUD operation.
                </li>
            </ul>
            <p>
                This approach follows best practices in software development and improves code readability and
                maintainability. For further insights on the DAO pattern, I referred to <a class="inline-link"
                    href="https://www.baeldung.com/java-dao-pattern">Baeldung's DAO pattern</a>.
            </p>
        </section>
    </article>
    <?php
    include_once("footer.inc");
    ?>
</body>

</html>