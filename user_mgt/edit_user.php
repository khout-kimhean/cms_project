<?php
require '../vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

$alertType = "";
$alertMessage = "";

// Initialize variables
$id = "";
$name = "";
$email = "";
$user_type = "";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];

    // // Debugging statements
    // echo "ID: $id<br>";
    // echo "Name: $name<br>";
    // echo "Email: $email<br>";
    // echo "User Type: $user_type<br>";

    // Prepare and execute the SQL statement
    $sql = "UPDATE login_register SET name = ?, email = ?, user_type = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $alertType = "danger";
        $alertMessage = "Error preparing SQL statement: " . $conn->error;
    } else {
        $stmt->bind_param("sssi", $name, $email, $user_type, $id);

        if ($stmt->execute()) {
            $alertType = "success";
            $alertMessage = "User updated successfully.";
        } else {
            $alertType = "danger";
            $alertMessage = "Error updating user: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Retrieve user data for editing
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT name, user_type, email FROM login_register WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $alertType = "danger";
        $alertMessage = "Error preparing SQL statement: " . $conn->error;
    } else {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($name, $user_type, $email);
                $stmt->fetch();
            } else {
                $alertType = "warning";
                $alertMessage = "User not found.";
            }
        } else {
            $alertType = "danger";
            $alertMessage = "Error executing the SQL query: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/user_mgt/edit_user.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>

<body>

    <div class="container">
        <aside>
            <div class="toggle">
                <div class="logo">
                    <img src="../images/logo/logo.jpg">
                    <h2>FTB <span class="danger">Bank</span></h2>
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
                <a href="../contact/contact.php">
                    <span class="fa fa-address-card">
                    </span>
                    <h3>Contact</h3>
                </a>
                <a href="../data_store/data_mgt.php">
                    <span class="fa fa-upload">
                    </span>
                    <h3>Data Store</h3>
                </a>

                <!-- <a href="../data_store/list_upload.php">
                    <span class="material-icons-sharp">
                        inventory
                    </span>
                    <h3>View File</h3>
                </a> -->
                <a href="../assessment/assessment.php">
                    <span class="fa fa-address-book">
                        <!-- fab fa-app-store-ios -->
                    </span>
                    <h3>Assessment</h3>
                </a>

                <a href="../user_mgt/user_management.php" class="active">
                    <span class="fa fa-user-circle">
                    </span>
                    <h3>User Mgt</h3>
                </a>
                <a href="../to_do_list/todo_management.php">
                    <span class="fa fa-list-alt">
                    </span>
                    <h3>To-do List</h3>
                </a>
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

            <div class="container2">
                <div class="back_button">
                    <a href="../user_mgt/user.php" class="back-button">
                        <i class="fa fa-chevron-circle-left" style="font-size: 28px"> Back</i>
                    </a>
                </div>

                <h2>Edit User</h2>

                <form method="post" action="">

                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                    <div class="select_role">
                        <div class="user">
                            <label class="label_input" for="role">User Name</label>
                            <input type="text" name="name" required placeholder="Name"
                                value="<?php echo htmlspecialchars($name); ?>">
                            <label class="label_input" for="role">User Email</label>
                            <input type="text" name="email" required placeholder="Email"
                                value="<?php echo htmlspecialchars($email); ?>">
                        </div>
                        <select name="user_type">
                            <option value="admin" <?php if ($user_type === "admin")
                                echo "selected"; ?>>Admin</option>
                            <option value="card payment team" <?php if ($user_type === "card payment team")
                                echo "selected"; ?>>Card Payment Team</option>
                            <option value="digital branch team" <?php if ($user_type === "digital branch team")
                                echo "selected"; ?>>Digital Branch Team</option>
                            <option value="atm team" <?php if ($user_type === "atm team")
                                echo "selected"; ?>>ATM Team
                            </option>
                            <option value="terminal team" <?php if ($user_type === "terminal team")
                                echo "selected"; ?>>Terminal Team
                            </option>
                            <option value="user" <?php if ($user_type === "user")
                                echo "selected"; ?>>User</option>
                        </select>

                    </div>


                    <input type="submit" name="submit" value="Save" class="form-btn">
                    <?php if ($alertMessage !== ""): ?>
                    <div class="alert alert-<?php echo $alertType; ?>" role="alert">
                        <?php echo $alertMessage; ?>
                    </div>
                    <?php endif; ?>
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
                        <small class="text-muted">Admin</small>
                    </div>
                    <div class="profile-photo">
                        <img src="../images/logo/logo.jpg">
                    </div>
                </div>

            </div>

            <div class="user-profile">
                <div class="logo">
                    <a href="https://ftb.com.kh/en/">
                        <img src="../images/logo/logo.jpg">
                        <h2>FTB Bank </h2>
                        <p>Welcome to FTB Bank</p>
                    </a>
                </div>
            </div>

            <div class="reminders">
                <div class="header">
                    <h2>Reminders</h2>
                    <span class="material-icons-sharp">
                        notifications_none
                    </span>
                </div>

                <div class="notification">
                    <div class="icon">
                        <span class="material-icons-sharp">
                            volume_up
                        </span>
                    </div>
                    <div class="content">
                        <div class="info">
                            <h3>Support Time</h3>
                            <small class="text_muted">
                                08:00 AM - 5:00 PM
                            </small>
                        </div>
                        <span class="material-icons-sharp">
                            more_vert
                        </span>
                    </div>
                </div>

                <div class="notification deactive">
                    <div class="icon">
                        <span class="material-icons-sharp">
                            edit
                        </span>
                    </div>
                    <div class="content">
                        <div class="info">
                            <h3>Open Time</h3>
                            <small class="text_muted">
                                08:00 AM - 5:00 PM
                            </small>
                        </div>
                        <span class="material-icons-sharp">
                            more_vert
                        </span>
                    </div>
                </div>

                <div class="notification add-reminder">
                    <div>
                        <span class="material-icons-sharp">
                            add
                        </span>
                        <h3>Add Reminder</h3>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- <script src="orders.js"></script> -->
    <script src="../script/index.js"></script>
</body>

</html>