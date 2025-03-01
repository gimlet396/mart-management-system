<?php
// Start the session
session_start();

// Database connection
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'mart_management_system'; 

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Registration logic
if (isset($_POST['signup'])) {
    $fullname = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['pswd'];
    $re_password = $_POST['re_pswd'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit('Email is not valid!');
    }

    // Validate password length
    if (strlen($password) < 8) {
        exit('Password must be at least 8 characters long!');
    }

    // Check if passwords match
    if ($password !== $re_password) {
        exit('Passwords do not match!');
    }

    // Hash the password using MD5
    $hashpassword = md5($password);

    // Insert into database
    if ($stmt = $con->prepare('INSERT INTO Admin (full_name, email, hashpassword) VALUES (?, ?, ?)')) {
        $stmt->bind_param('sss', $fullname, $email, $hashpassword);
        $stmt->execute();
        $_SESSION['success_message'] = 'You have successfully registered! You can now login!';
        header("Location: index.php"); // Redirect to the same page or login page
        exit();
    } else {
        echo 'Could not prepare statement!';
    }
}

// Login logic
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit('Email is not valid!');
    }

    // Check if the account exists
    if ($stmt = $con->prepare('SELECT hashpassword FROM Admin WHERE email = ?')) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashpassword);
            $stmt->fetch();

            // Verify password using MD5
            if (md5($password) === $hashpassword) {
                $_SESSION['user_email'] = $email; // Store user email in session
                header("Location: ../Dashboard/index.php"); // Redirect to dashboard
                exit();
            } else {
                echo 'Incorrect password!';
            }
        } else {
            echo 'No account found with that email!';
        }
    } else {
        echo 'Could not prepare statement!';
    }
}

$con->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup and Login Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main">
        <input type="checkbox" id="chk">

        <div class="login">
            <form action="index.php" method="POST"> <!-- Update the action to your PHP script -->
                <label for="chk">Login</label>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" name="login" id="button" value="Login">
                <p id="forgot-password"><a href="#">Forgot Password?</a></p>
                <p>Don't have an account? Create an account</p>
            </form>
        </div>

        <div class="signup">
            <form action="index.php" method="POST"> <!-- Update the action to your PHP script -->
                <label for="chk">Register</label>
                <input type="text" name="name" placeholder="Fullname" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="pswd" placeholder="Password" required>
                <input type="password" name="re_pswd" placeholder="Re-enter Password" required> <!-- Unique name for re-enter password -->
                <input type="submit" name="signup" id="button" value="Sign Up">
            </form>
        </div>
    </div>
</body>
</html>