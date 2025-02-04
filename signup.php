<?php
/**
 * Sign up page for SmartGlow. Allows users to sign up as a user or manager.
 * Users must provide a user ID, name, password, and confirm password.
 * Managers must provide a secret ID to sign up.
 * 
 * @author Chong Yi Yang
 * @version 1.0
 * @file signup.php
 */


session_start();
require_once("utilities.php");

// If logged in and trying to access sign up page, redirect to index.php
if (isset($_SESSION["user_id"])) {
    header("location: index.php");
    exit();
}

// Initialise error messages and input values
$errors = [
    "user-id" => "",
    "name" => "",
    "password" => "",
    "confirm-password" => "",
    "secret" => ""
];

$input = [
    "user-id" => "",
    "name" => "",
    "password" => "",
    "confirm-password" => "",
    "secret" => ""
];

// If user is trying to sign up with POST request
if (isset($_POST["user-id"])) {
    // Get input data from POST request
    $userId = sanitise_input($_POST["user-id"]);
    $name = sanitise_input($_POST["user-name"]);
    $password = sanitise_input($_POST["password"]);
    $confirmPassword = sanitise_input($_POST["confirm-password"]);
    $role = sanitise_input($_POST["role"]);

    $secret = "";

    // Check if user ID, name, password, and confirm password are empty
    if (empty($userId)) {
        $errors["user-id"] = "User ID is required.";
    }

    if (empty($name)) {
        $errors["name"] = "Name is required.";
    }

    if (empty($password)) {
        $errors["password"] = "Password is required.";
    }

    if (empty($confirmPassword)) {
        $errors["confirm-password"] = "Confirm Password is required.";
    }
    // Check if secret ID is empty for managers
    if ($role == "manager") {
        $secret = sanitise_input($_POST["secret"]);
        if (empty($secret)) {
            $errors["secret"] = "Secret ID is required for managers.";
        }
    }

    // Set POST data to input array
    $input = [
        "user-id" => $userId,
        "name" => $name,
        "password" => $password,
        "confirm-password" => $confirmPassword,
        "secret" => $secret
    ];

    // Check if there are no errors, then further validate input data
    if (!array_filter($errors)) {
        // Validate User ID with letters and numbers only
        if (!preg_match("/^[a-zA-Z0-9]{5,}$/", $userId)) {
            $errors["user-id"] = "ID must be at least 5 characters, with letters and spaces only.";
        }

        // Validate Name with letters and spaces only
        if (!preg_match("/^[a-zA-Z ]{2,}$/", $name)) {
            $errors["name"] = "Name must be at least 2 characters, with letters and spaces only.";
        }

        // Validate Password with 8+ characters, uppercase, lowercase, digits, and special characters
        if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[\W]/", $password)) {
            $errors["password"] = "Password must be 8+ characters, with upper/lowercase letters, digits, and special characters.";
        }

        // Check if User ID already exists in the database
        $userDAO = new UserDAO();
        $user = $userDAO->getUserById($userId);

        // If user ID already exists, set error message
        if ($user !== null) {
            $errors["user-id"] = "User ID already exists.";
        }

        // Check if Password and Confirm Password match
        if ($password !== $confirmPassword) {
            $errors["confirm-password"] = "Passwords do not match.";
        }

        // Check if secret ID is valid for managers
        if (strtolower($role) === "manager" && $secret !== "1234") {
            $errors["secret"] = "Invalid secret ID.";
        }

        // If there are no errors, insert user into the database
        if (!array_filter($errors)) {
            // Hash password before inserting into database
            $userDAO->insertUser($userId, hash("sha256", $password), $name, $role);
            // Redirect to login page based on user role
            $endPoint = $role === "manager" ? "login.php?role=manager" : "login.php";
            header("location: $endPoint");
            exit();
        }
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
            <h2><?php echo isset($_GET['role']) && $_GET['role'] == 'manager' ? 'Manager' : 'User'; ?> Sign Up</h2>
            <!-- If the login page is for manager then put action to the role=manager page -->
            <?php if (isset($_GET['role']) && $_GET['role'] == 'manager'): ?>
                <form action="signup.php?role=manager" method="post">
                <?php else: ?>
                    <form action="signup.php" method="post">
                    <?php endif; ?>

                    <label for="user-id">User ID:</label>
                    <input type="text" id="user-id" name="user-id" <?php echo 'value="' . $input['user-id'] . '"' ?>>
                    <span class="error-msg">
                        <?php echo $errors["user-id"]; ?>
                    </span>

                    <label for="user-name">Name:</label>
                    <input type="text" id="user-name" name="user-name" <?php echo 'value="' . $input['name'] . '"' ?>>
                    <span class="error-msg">
                        <?php echo $errors["name"]; ?>
                    </span>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" <?php echo 'value="' . $input['password'] . '"' ?>>
                    <span class="error-msg">
                        <?php echo $errors["password"]; ?>
                    </span>

                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" name="confirm-password" <?php echo 'value="' . $input['confirm-password'
                    ] . '"' ?>>
                    <span class="error-msg">
                        <?php echo $errors["confirm-password"]; ?>
                    </span>

                    <?php if (isset($_GET['role']) && $_GET['role'] == 'manager'): ?>
                        <label for="secret">Secret ID &#40;Type 1234&#41;</label>
                        <input type="text" id="secret" name="secret" <?php echo 'value="' . $input['secret'] . '"' ?>>
                        <span class="error-msg">
                            <?php echo $errors["secret"]; ?>
                        </span>
                    <?php endif; ?>

                    <!-- Set hidden input "role" based on the user login -->
                    <input type="hidden" id="role" name="role"
                        value="<?php echo isset($_GET['role']) && $_GET['role'] == 'manager' ? 'manager' : 'user'; ?>">


                    <input type="submit" value="Sign up">
                    <span class="error-msg"></span>
                </form>
                <p>Don't have an account? <a href="login.php">Login here</a></p>
                <?php if (isset($_GET['role']) && $_GET['role'] == 'manager'): ?>
                    <p>Are you a normal user? <a href="signup.php">Sign up as user</a></p>
                <?php else: ?>
                    <p>Are you a manager? <a href="signup.php?role=manager">Sign up as manager</a></p>
                <?php endif; ?>
        </section>

    </main>


    <?php
    include_once("footer.inc");
    ?>
</body>

</html>