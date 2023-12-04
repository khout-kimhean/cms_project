<?php
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

session_start();

$error = array(); // Initialize an error array

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $select = "SELECT * FROM login_register WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['chat'] = $row['name'];

            if ($row['user_type'] == 'admin') {
                header('location: ../Admin Dashboard/admin.php');
                exit();
            } elseif ($row['user_type'] == 'user') {
                header('location: ../Admin Dashboard/user.php');
                exit();
            } else {
                $error[] = 'Invalid user type';
            }
        } else {
            $error[] = 'Incorrect password!';
        }
    } else {
        $error[] = 'User not found';
    }
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
    <title>login form</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" type="text/css" href="../styles/user_mgt/login_form.css">

</head>

<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>login now</h3>
            <?php
            if (isset($error)) {
                foreach ($error as $error) {
                    echo '<span class="error-msg">' . $error . '</span>';
                }
                ;
            }
            ;
            ?>
            <input type="email" name="email" required placeholder="enter your email">
            <input type="password" name="password" required placeholder="enter your password">
            <input type="submit" name="submit" value="login now" class="form-btn">
            <p><a href="forget_password.php">Forget Password</a></p>

        </form>

    </div>
    <script src="index.js"></script>
</body>

</html>