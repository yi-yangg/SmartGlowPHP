<?php
/**
 * Login page for SmartGlow. Users can login as a user or manager.
 * If user is already logged in, redirect to index.php
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file login.php
 */

session_start();
require_once("utilities.php");
// If logged in and trying to access login page, redirect to index.php
if (isset($_SESSION["user_id"])) {
    header("location: index.php");
    exit();
}
$errMsg = "";


// If user is trying to login with POST request
if (isset($_POST["user-id"])) {
    $userId = sanitise_input($_POST["user-id"]);
    // Hash the password before comparing
    $password = hash("sha256", sanitise_input($_POST["password"]));
    $role = sanitise_input($_POST["role"]);
    // Get user from MySQL database
    $userDAO = new UserDAO();
    $user = $userDAO->getUser($userId, $password, $role);

    // If user is found, set session variables and redirect to index.php
    if ($user != null) {
        $_SESSION["user_id"] = $userId;
        $_SESSION["user_type"] = $role;
        header("location: index.php");
        exit();
    } else {
        $errMsg = "Invalid user ID or password.";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Login into SmartGlow">
    <meta name="keywords" content="Smart glow, login">
    <meta name="author" content="Chong Yi Yang">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SmartGlow</title>
    <!-- Icon beside title link -->
    <link rel="icon" href="images/smartglow.ico" type="image/x-icon">

    <!-- Link for CSS style sheet -->
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <?php
    include_once("header.inc");
    ?>
    <main class="login-main">
        <section class="login-container">
            <h1>SmartGlow</h1>
            <h2><?php echo isset($_GET['role']) && $_GET['role'] == 'manager' ? 'Manager' : 'User'; ?> Login</h2>
            <!-- If the login page is for manager then put action to the role=manager page -->
            <?php if (isset($_GET['role']) && $_GET['role'] == 'manager'): ?>
                <form action="login.php?role=manager" method="post">
                <?php else: ?>
                    <form action="login.php" method="post">
                    <?php endif; ?>
                    <label for="user-id">User ID:</label>
                    <input type="text" id="user-id" name="user-id">

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">

                    <!-- Set hidden input "role" based on the user login -->
                    <input type="hidden" id="role" name="role"
                        value="<?php echo isset($_GET['role']) && $_GET['role'] == 'manager' ? 'manager' : 'user'; ?>">

                    <input type="submit" value="Login">
                    <span class="error-msg"><?php echo $errMsg ?></span>
                </form>
                <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
                <?php if (isset($_GET['role']) && $_GET['role'] == 'manager'): ?>
                    <p>Are you a normal user? <a href="login.php">Login as user</a></p>
                <?php else: ?>
                    <p>Are you a manager? <a href="login.php?role=manager">Login as manager</a></p>
                <?php endif; ?>
        </section>

    </main>


    <?php
    include_once("footer.inc");
    ?>
</body>

</html>