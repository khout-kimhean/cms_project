<?php

include '../dashboard/check_access.php';
include '../connect/role_access.php';

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

$error = array();
$success_message = ''; // Initialize the success message variable



$sql = "SELECT * FROM login_register";

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $user_type = $_POST['user_type'];

    if (strtolower($password) === strtolower($name)) {
        $error[] = 'Password cannot be the same as your name.';
    } elseif (strlen($password) <= 8) {
        $error[] = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/[A-Za-z]/', $password)) {
        $error[] = 'Password must contain at least one letter.';
    } elseif (!preg_match('/\d/', $password)) {
        $error[] = 'Password must contain at least one number.';
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $error[] = 'Password must contain at least one special symbol.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error[] = 'Password must contain at least one uppercase letter.';
    } elseif ($password != $cpassword) {
        $error[] = 'Passwords do not match.';
    } else {
        // Use the hashed password for database storage
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user data using prepared statements
        $insert = $conn->prepare("INSERT INTO login_register (name, email, password, user_type) VALUES (?, ?, ?, ?)");
        $insert->bind_param("ssss", $name, $email, $hash, $user_type);

        if ($insert->execute()) {
            $success_message = 'User created successfully.';
        } else {
            $error[] = 'Error: ' . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/user_mgt/login_register.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <title>Admin Dashboard</title>

</head>

<body>
    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo">
                    <img src="../images/logo/logo.jpg">
                    <!-- <h2>FTB <span class="danger">Bank</span></h2> -->
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">
                        <!-- close -->
                    </span>
                </div>
            </div>

            <div class="sidebar">
                <a href="../dashboard/dashboard.php">
                    <span class="material-icons-sharp">
                        dashboard
                    </span>
                    <h3>Dashboard</h3>
                </a>

                <!-- <a href="../data_store/search.php">
                    <span class="fa fa-search">
                    </span>
                    <h3>Search</h3>
                </a> -->
                <!-- <a href="../contact/contact.php">
                    <span class="fa fa-address-card">
                    </span>
                    <h3>Contact</h3>
                </a> -->
                <a href="../file/file_mgt.php" <?php echo isLinkDisabled('file_mgt.php'); ?>>
                    <span class="fa fa-upload">
                    </span>
                    <h3>Documents</h3>
                </a>

                <!-- <a href="../data_store/list_upload.php">
                    <span class="material-icons-sharp">
                        inventory
                    </span>
                    <h3>View File</h3>
                </a> -->
                <a href="../assessment/assessment.php" <?php echo isLinkDisabled('assessment.php'); ?>>
                    <span class="fa fa-address-book">
                        <!-- fab fa-app-store-ios -->
                    </span>
                    <h3>IB User Assessment</h3>
                </a>

                <a href="../user_mgt/user_management.php" <?php echo isLinkDisabled('user_management.php'); ?>
                    class="active">
                    <span class="fa fa-user-circle">
                    </span>
                    <h3>User Mgt</h3>
                </a>
                <!-- <a href="../to_do_list/todo_management.php">
                    <span class="fa fa-list-alt">
                    </span>
                    <h3>To-do List</h3>
                </a> -->
                <!-- <a href="../data_store/data_mgt.php">
                    <span class="fa fa-briefcase">
                    </span>
                    <h3>Stock Mgt</h3>
                </a> -->


                <a href="../user_mgt/logout.php">
                    <span class="material-icons-sharp">
                        logout
                    </span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>
        <main>
            <div class="form-container">
                <form action="" method="post" id="create_user_form">
                    <div class="back_button">
                        <a href="../user_mgt/user.php" class="back-button">
                            <i class="fa fa-chevron-circle-left" style="font-size: 26px">
                                <h1>Back</h1>
                            </i>
                        </a>
                    </div>
                    <h3>Create User</h3>

                    <?php
                    if (!empty($success_message)) {
                        echo '<div class="alert alert-success">' . $success_message . '</div>';
                    } elseif (!empty($error)) {
                        foreach ($error as $errorMsg) {
                            echo '<div class="alert alert-danger">' . $errorMsg . '</div>';
                        }
                    }
                    ?>
                    <div class="user">
                        <label class="label_input" for="role">User Name</label>
                        <input type="text" name="name" required placeholder="Enter UserName">
                        <label class="label_input" for="role">User Email</label>
                        <input type="email" name="email" required placeholder="Enter Email">
                    </div>
                    <div class="user">
                        <label class="label_input" for="role">Password</label>
                        <input type="password" name="password" id="password" required placeholder="Enter Password">
                        <label class="label_input" for="role">C password</label>
                        <input type="password" name="cpassword" required placeholder="Confirm Password">
                        <?php
                        if (isset($error) && in_array('Passwords do not match!', $error)) {
                            echo '<span class="error-msg">Passwords do not match!</span>';
                        }
                        ?>

                    </div>

                    <div class="input">
                        <label class="label_input">User Type</label>
                        <select name="user_type">
                            <option value="admin">Admin</option>
                            <option value="card payment team">Card Payment Team</option>
                            <option value="digital branch team">Digital Branch Team</option>
                            <option value="atm team">ATM Team</option>
                            <option value="terminal team">Terminal Team</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <input type="submit" name="submit" value="Create User" class="form-btn">


                </form>
            </div>

        </main>
        <div class="right-section">
            <div class="nav">
                <button id="menu-btn">
                    <span class="material-icons-sharp">
                        menu
                    </span>
                </button>
                <div class="dark-mode">
                    <span class="material-icons-sharp active">
                        light_mode
                    </span>
                    <span class="material-icons-sharp">
                        dark_mode
                    </span>
                </div>

                <div class="profile">
                    <div class="info">
                        <p>Welcome</p>
                        <small class="text-muted">
                            <?php echo $_SESSION['user_name']; ?>
                        </small>
                    </div>
                    <div class="profile-photo">
                        <img src="../images/logo/user.png">
                    </div>
                </div>

            </div>

        </div>
    </div>
    <script src="../script/role_check.js"></script>
    <!-- <script src="orders.js"></script> -->
    <script src="../script/index.js"></script>
</body>

</html>