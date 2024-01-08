<?php

// Include the file with the access check
include '../dashboard/check_access.php';
include '../connect/role_access.php';
// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'demo';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$totalUsersSql = "SELECT COUNT(*) as total_users FROM login_register";
$totalUsersResult = $conn->query($totalUsersSql);

if ($totalUsersResult) {
    $row = $totalUsersResult->fetch_assoc();
    $_SESSION['total_users'] = $row['total_users'];
} else {
    echo "Error: " . $conn->error;
}

// total file
$totalFileSql = "SELECT COUNT(*) as total_file FROM upload_file ";
$totalFileResult = $conn->query($totalFileSql);
if ($totalFileResult) {
    $row = $totalFileResult->fetch_assoc();
    $_SESSION['total_file'] = $row['total_file'];
} else {
    echo "Error: " . $conn->error;
}

// total file deleted
$totalDeleteSql = "SELECT COUNT(*) as total_delete FROM recover_file ";
$totalDeleteResult = $conn->query($totalDeleteSql);
if ($totalDeleteResult) {
    $row = $totalDeleteResult->fetch_assoc();
    $_SESSION['total_delete'] = $row['total_delete'];
} else {
    echo "Error: " . $conn->error;
}

$error = array();

$sql = "SELECT * FROM login_register";
$result = $conn->query($sql);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="dashboard.css">
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
                <a href="./dashboard.php" class="active">
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

                <a href="../user_mgt/user_management.php" <?php echo isLinkDisabled('user_management.php'); ?>>
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
            <h1>Analytics</h1>
            <!-- Analyses -->
            <div class="analyse">
                <div class="sales" onclick="window.location.href='#';" style="cursor: pointer;">
                    <div class="status">
                        <div class="info">
                            <h3>Total User </h3>
                            <h1>
                                <?php echo $_SESSION['total_users']; ?> user
                            </h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                <p>User</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="visits" onclick="window.location.href='#';" style="cursor: pointer;">
                    <div class="status">
                        <div class="info">
                            <h3>Total File Upload</h3>
                            <h1>
                                <?php echo $_SESSION['total_file']; ?> file
                            </h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                <p>File</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="searches" onclick="window.location.href='#';" style="cursor: pointer;">
                    <div class="status">
                        <div class="info">
                            <h3>Total File Deleted</h3>
                            <h1>
                                <?php echo $_SESSION['total_delete']; ?> file
                            </h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                <p>Delete</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="new-users">
                <h2>Option</h2>
                <div class="user-list">
                    <div class="user">
                        <a href="../dashboard/chatgpt.php" <?php echo isLinkDisabled('chat.php'); ?>>
                            <!-- <a href="../chatbot/chat.php" <?php echo isLinkDisabled('chat.php'); ?>> -->
                            <img src="../images/background/chat.png" alt="ChatBot">
                            <h2>ChatBot</h2>
                            <p>User ChatBot Here</p>
                        </a>
                    </div>
                    <div class="user">
                        <a href="../file/datachat.php" <?php echo isLinkDisabled('datachat.php'); ?>>
                            <!-- <i class="fa fa-upload" style="font-size:75px;color:blue"></i> -->
                            <img src="../images/file/file.png" alt="Upload File">
                            <h2>Data Chat</h2>
                            <p>Input data for chat</p>
                        </a>
                    </div>
                    <div class="user">
                        <a href="../file/file_mgt.php" <?php echo isLinkDisabled('file_mgt.php'); ?>>
                            <img src="../images/file/upload.png" alt="Show File">
                            <h2>Store File</h2>
                            <p>Upload and View file</p>
                        </a>
                    </div>
                    <div class="user">
                        <a href="../find_error/read_file.php" <?php echo isLinkDisabled('read_file.php'); ?>>
                            <img src="../images/file/file2.png" alt="More">
                            <h2>Find Error</h2>
                            <p>Short Error in Log File</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- End of New Users Section -->

            <!-- Recent Orders Table -->
            <div class="recent-orders">
                <h2>User</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>UserID</th>
                            <th>UserName</th>
                            <th>Email</th>
                            <th>Type</th>
                            <!-- <th>Details</th> -->
                            <!-- <input type="submit" name="delete_user" value="Delete"> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $id = 0; // Initialize a counter variable
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if ($id < 3) { // Display only 3 users
                                    echo '<tr>
                                        <td>' . ($id + 1) . '</td>
                                        <td>' . $row['name'] . '</td>
                                        <td>' . $row['email'] . '</td>
                                        <td>' . $row['user_type'] . '</td>
                                        <td>
                                        <form method="post">
                                            <input type="hidden" name="user_id" value="' . $row['id'] . '">
                                            
                                        </form>
                                        </td>
                                    </tr>';
                                    $id++; // Increment the counter
                                } else {
                                    break; // Exit the loop after displaying 3 users
                                }
                            }
                        } else {
                            echo "<tr><td colspan='5'>No users found.</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>


                <a href="../user_mgt/showuser.php" <?php echo isLinkDisabled('showuser.php'); ?>>Show All</a>
            </div>
            <!-- End of Recent Orders -->

        </main>
        <!-- End of Main Content -->

        <!-- Right Section -->
        <div class="right-section">
            <div class="nav">

                <button id="menu-btn">
                    <span class="material-icons-sharp">
                        menu
                    </span>
                </button>
                <a href="../dashboard/notification.php" class="notification"
                    <?php echo isLinkDisabled('notification.php'); ?>>
                    <i><img src="../images/logo/reminder2.png"></i>
                    <span class="count">1</span>
                </a>
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
                    <!-- <a href="../user_mgt/change_password.php"> -->
                    <div class="profile-photo">
                        <img src="../images/logo/user.png">
                    </div>
                    <!-- </a> -->
                </div>

            </div>
            <!-- End of Nav -->

            <div class="user-profile" onclick="window.location.href='../dashboard/chatgpt.php'">
                <div class="logo">
                    <img src="../images/logo/logo.jpg" alt="FTB Bank Logo">
                    <h2>FTB Bank</h2>
                    <p>Welcome to FTB Bank</p>
                </div>
            </div>

            <div class="reminders">
                <div class="header">
                    <h2>Reminders</h2>
                    <!-- <a href="../to_do_list/todo_management.php"></a>
                    <span class="fa fa-bell"> </span>
                    </a> -->
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
                                08:00 AM - 12:00 PM
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
                                01:00 PM - 5:00 PM
                            </small>
                        </div>
                        <span class="material-icons-sharp">
                            more_vert
                        </span>
                    </div>
                </div>
                <div class="notification add-reminder">
                    <a href="../dashboard/testchat.php" <?php echo isLinkDisabled('testchat.php'); ?>>
                        <div>
                            <span class="material-icons-sharp">
                                add
                            </span>
                            <h3>Add Reminder</h3>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
    <script src="../script/role_check.js"></script>
    <script src="../script/index.js"></script>
</body>

</html>