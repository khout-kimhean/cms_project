<?php
include '../connect/role_access.php';
include '../dashboard/check_access.php';

$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'demo';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$alertType = "";
$alertMessage = "";

$error = array();

$sql = "SELECT * FROM login_register";
$result = $conn->query($sql);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_GET['id']) ? $_GET['id'] : 0; // Assuming you have the user ID in the URL
    $permissions = isset($_POST['permissions']) ? $_POST['permissions'] : array();
    if (!empty($permissions)) {
        $permissionsPlaceholders = implode(', ', array_fill(0, count($permissions), '?'));
        $sql = "UPDATE login_register SET permissions = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error: " . $conn->error);
        }
        $stmt->bind_param("ssi", $permissionsPlaceholders, $id, $permissions);
        if ($stmt->execute()) {
            $alertType = "success";
            $alertMessage = "Permissions added successfully.";
        } else {
            $alertType = "danger";
            $alertMessage = "Error adding permissions: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $alertType = "danger";
        $alertMessage = "No permissions selected.";
    }
}
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $id = $_GET['id'];
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT name, user_type FROM login_register WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($name, $user_type);
            $stmt->fetch();
        } else {
            echo "Content not found.";
        }
    } else {
        echo "Error executing the SQL query: " . $stmt->error;
    }
    $stmt->close();
} else {
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
    <link rel="stylesheet" type="text/css" href="../styles/user_mgt/assign_function.css">
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
                    <h3>Store File</h3>
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
                    <h3>Assessment</h3>
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
            <div class="container2">
                <h2>Add Function to Role/Team User</h2>
                <div class="select_role">
                    <div class="user">
                        <label class="label_input" for="role">User Name</label>
                        <input type="text" name="name" required placeholder="Name"
                            value="<?php echo htmlspecialchars($name); ?>">
                        <label class="label_input" for="user type">User Type</label>
                        <input type="text" name="user type" required placeholder="User Type"
                            value="<?php echo htmlspecialchars($user_type); ?>">
                    </div>
                </div>
                <div class="select1">
                    <h2>Permission</h2>
                </div>
                <form action="" method="post">
                    <div class="box">
                        <div class="container3">
                            <input type="checkbox" id="permission-1" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-1" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>ChatBot</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-2" class="check-input">
                            <label for="input-2" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>DataChat</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-3" class="check-input">
                            <label for="input-3" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Find Error</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-4" class="check-input">
                            <label for="input-4" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Data Store</span>
                        </div>
                    </div>
                    <div class="box">
                        <div class="container3">
                            <input type="checkbox" id="input-5" class="check-input">
                            <label for="input-5" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Assessment</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-6" class="check-input">
                            <label for="input-6" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>User Management</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-7" class="check-input">
                            <label for="input-7" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>To_do List</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-8" class="check-input">
                            <label for="input-8" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Contact</span>
                        </div>
                    </div>
                    <input type="submit" name="submit" value="Save" class="form-btn">
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
    <script src="../script/role_check.js"></script>
    <script src="../script/index.js"></script>
</body>

</html>