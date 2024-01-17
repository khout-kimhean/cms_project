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
                    <!-- <h2>FTB <span class="danger">Bank</span></h2> -->
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
            <div class="container2">
                <div class="back_button">
                    <a href="../user_mgt/user.php" class="back-button">
                        <i class="fa fa-chevron-circle-left" style="font-size: 28px">
                            <h1>Back</h1>
                        </i>
                    </a>
                </div>
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
                            <span>Dashboard</span>
                        </div>

                    </div>
                    <div class="box">
                        <div class="container3">
                            <input type="checkbox" id="permission-2" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-2" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>File Mgt</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-3" class="check-input">
                            <label for="input-3" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Upload File</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-4" class="check-input">
                            <label for="input-4" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>View File</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-5" class="check-input">
                            <label for="input-5" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>View</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="permission-6" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-6" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>View Data</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-7" class="check-input">
                            <label for="input-7" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Edit Data</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-8" class="check-input">
                            <label for="input-8" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Report</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-9" class="check-input">
                            <label for="input-9" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Delete</span>
                        </div>
                    </div>
                    <div class="box">
                        <div class="container3">
                            <input type="checkbox" id="permission-10" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-10" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>User Mgt</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="permission-11" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-11" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>User</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-12" class="check-input">
                            <label for="input-12" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Create User</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-13" class="check-input">
                            <label for="input-13" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Edit User</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="permission-15" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-15" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Add Permission</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="permission-16" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-16" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Show User</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="permission-17" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-17" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Delete</span>
                        </div>

                    </div>
                    <div class="box">
                        <div class="container3">
                            <input type="checkbox" id="permission-18" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-18" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Find Error</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-19" class="check-input">
                            <label for="input-19" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Error in Log</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-20" class="check-input">
                            <label for="input-20" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Error</span>
                        </div>

                    </div>
                    <div class="box">
                        <div class="container3">
                            <input type="checkbox" id="permission-21" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-21" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Data Chat</span>
                        </div>

                    </div>
                    <div class="box">
                        <div class="container3">
                            <input type="checkbox" id="permission-22" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-22" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>ChatBot</span>
                        </div>

                    </div>
                    <div class="box">
                        <div class="container3">
                            <input type="checkbox" id="permission-23" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-23" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Assessment</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-24" class="check-input">
                            <label for="input-24" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Upload New</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-25" class="check-input">
                            <label for="input-25" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Upload Move</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="input-26" class="check-input">
                            <label for="input-26" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>Upload Resign</span>
                        </div>
                        <div class="container3">
                            <input type="checkbox" id="permission-27" name="permissions[]" value="ChatBot"
                                class="check-input">
                            <label for="permission-27" class="checkbox">
                                <svg viewBox="0 0 22 16" fill="none">
                                    <path d="M1 6.85L8.09677 14L21 1" />
                                </svg>
                            </label>
                            <span>List User</span>
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

        </div>
    </div>
    <script src="../script/role_check.js"></script>
    <script src="../script/index.js"></script>
</body>

</html>