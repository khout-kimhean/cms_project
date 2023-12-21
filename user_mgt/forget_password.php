<?php
// Database configuration (same as your login form)
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'demo';

// Create a database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

$error = array(); // Initialize an error array
$success_message = '';

if (isset($_POST['submit'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (strlen($new_password) < 8) {
        $error[] = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/[0-9]/', $new_password)) {
        $error[] = 'Password must contain at least one number.';
    } elseif (!preg_match('/[A-Z]/', $new_password)) {
        $error[] = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[^a-zA-Z0-9]/', $new_password)) {
        $error[] = 'Password must contain at least one symbol.';
    } elseif ($new_password != $confirm_password) {
        $error[] = 'Passwords do not match.';
    } else {
        // Use password_hash for secure password hashing
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        // Use prepared statement to prevent SQL injection
        $update = $conn->prepare("UPDATE login_register SET password = ? WHERE email = ?");
        $update->bind_param("ss", $new_password_hash, $email);

        if ($update->execute()) {
            $success_message = "Your password has been successfully reset.";
        } else {
            $error[] = "Error updating password: " . $conn->error;
        }

        $update->close();
    }
}

// Check if the user exists after the password change
$selectUser = $conn->prepare("SELECT * FROM login_register WHERE email = ?");
$selectUser->bind_param("s", $email);
$selectUser->execute();
$result = $selectUser->get_result();
$user = $result->fetch_assoc();

$selectUser->close();
$conn->close();
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <!-- Custom CSS file link -->
    <link rel="stylesheet" type="text/css" href="../styles/user_mgt/login_form.css">
</head>

<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>Reset Password</h3>
            <?php
            if (isset($error)) {
                foreach ($error as $err) {
                    echo '<span class="error-msg">' . $err . '</span>';
                }
            }
            if (!empty($success_message)) {
                // echo '<script>alert("' . $success_message . '");</script>';
                echo '<span class="error-msg">' . $success_message . '</span>';
            }
            ?>
            <input type="email" name="email" required placeholder="Enter your email">
            <input type="password" name="new_password" required placeholder="Enter your new password">
            <input type="password" name="confirm_password" required placeholder="Confirm your new password">
            <input type="submit" name="submit" value="Reset Password" class="form-btn">
            <p><a href="login.php">Back to Login</a></p>
        </form>
    </div>
    <script src="index.js"></script>
</body>

</html>