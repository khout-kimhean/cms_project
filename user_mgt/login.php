<?php
session_start();
// Database configuration
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

$errors = array(); // Initialize an errors array

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $select = "SELECT id, name, user_type, password FROM login_register WHERE email = ?";
    $stmt = $conn->prepare($select);

    if ($stmt === false) {
        die("Error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_role'] = $row['user_type']; // Store user role in the session

            // Regenerate session ID
            session_regenerate_id(true);

            $allowed_user_types = ['admin', 'user', 'card payment team', 'digital branch team', 'atm team', 'terminal team'];

            if (in_array($row['user_type'], $allowed_user_types)) {
                header('Location: auth.php');
                exit();
            } else {
                $errors[] = 'Invalid user type';
            }
        } else {
            $errors[] = 'Incorrect password!';
        }
    } else {
        $errors[] = 'User not found';
    }

    $stmt->close();
}

// Close the database connection
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
    <title>Login Form</title>
    <link rel="stylesheet" type="text/css" href="../styles/user_mgt/login_form.css">

</head>

<body>
    <div class="form-container">
        <form action="" method="post">
            <!-- <img src="../images/logo/logo.jpg" alt=""> -->
            <h3>Login Now</h3>
            <?php
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo '<span class="error-msg">' . $error . '</span>';
                }
            }
            ?>
            <input type="email" name="email" required placeholder="Enter your email">
            <input type="password" name="password" required placeholder="Enter your password">
            <input type="submit" name="submit" value="Login Now" class="form-btn">
            <p><a href="forget_password.php">Forget Password</a></p>
        </form>
    </div>
    <script src="index.js"></script>
</body>

</html>