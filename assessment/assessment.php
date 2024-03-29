<?php
// session_start();
include '../connect/role_access.php';
// Include the file with the access check
include '../dashboard/check_access.php';

// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'demo';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
    <link rel="stylesheet" type="text/css" href="../styles/assessment/assessment.css">
    <title>Assessment</title>
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
                        close
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
                <a href="../assessment/assessment.php" <?php echo isLinkDisabled('assessment.php'); ?> class="active">
                    <span class="fa fa-address-book">
                        <!-- fab fa-app-store-ios -->
                    </span>
                    <h3>IB User Assessment</h3>
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
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main>
            <Div class="container2">
                <h1 class="h1">User Assessment</h1>
                <!-- Analyses -->

                <!-- <h1>Assessment</h1> -->
                <div class="analyse">
                    <div class="status" onclick="window.location.href='../assessment/upload_new.php';"
                        style="cursor: pointer;">
                        <div class="status">
                            <div class="info">
                                <h1>New User</h1>
                                <p>Upload User</p>
                            </div>
                            <div>
                                <img src="../images/usermgt/adduser.png">
                            </div>
                        </div>
                    </div>
                    <div class="status" onclick="window.location.href='../assessment/upload_move.php';"
                        style="cursor: pointer;">
                        <div class="status">
                            <div class="info">
                                <h1>Move User</h1>
                                <p>Upload User</p>
                            </div>
                            <div>
                                <img src="../images/usermgt/usermgt.png">
                            </div>
                        </div>
                    </div>

                    <div class="visits" onclick="window.location.href='../assessment/assessment_user.php';"
                        style="cursor: pointer;">
                        <div class="status">
                            <div class="info">
                                <h1>List Move</h1>
                                <p>User assessment</p>
                            </div>
                            <div>
                                <img src="../images/file/file.png">
                            </div>
                        </div>
                    </div>
                    <div class="visits" onclick="window.location.href='../assessment/list_new_user.php';"
                        style="cursor: pointer;">
                        <div class="status">
                            <div class="info">
                                <h1>List New</h1>
                                <p>User Move</p>
                            </div>
                            <div>
                                <img src="../images/file/file2.png">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="analyse">
                    <div class="sales" onclick="window.location.href='../assessment/newuser_assessment.php';"
                        style="cursor: pointer;">
                        <div class="status">
                            <div class="info">
                                <h1>New User</h1>
                                <p>Manual Create</p>
                            </div>
                            <div>
                                <img src="../images/usermgt/adduser.png">
                            </div>
                        </div>
                    </div>
                    <div class="visits" onclick="window.location.href='../assessment/search_move.php';"
                        style="cursor: pointer;">
                        <div class="status">
                            <div class="info">
                                <h1>Move User</h1>
                                <p>Manual Create</p>
                            </div>
                            <div>
                                <img src="../images/usermgt/usermgt.png">
                            </div>
                        </div>
                    </div>
                    <div class="searches" onclick="window.location.href='../assessment/search_resign.php';"
                        style="cursor: pointer;">
                        <div class="status">
                            <div class="info">
                                <h1>User Resign</h1>
                                <p>Manual Create</p>
                            </div>
                            <div>
                                <img src="../images/usermgt/move.png">
                            </div>
                        </div>
                    </div>
                    <div class="status" onclick="window.location.href='../assessment/assessment_list.php';"
                        style="cursor: pointer;">
                        <div class="status">
                            <div class="info">
                                <h1>List User</h1>
                                <p>Assessment</p>
                            </div>
                            <div>
                                <img src="../images/file/file1.png">
                            </div>
                        </div>
                    </div>
                </div> -->
            </Div>

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
            <!-- End of Nav -->

        </div>
    </div>
    <script src="../script/role_check.js"></script>
    <!-- <script src="orders.js"></script> -->
    <script src="../script/index.js"></script>
</body>

</html>