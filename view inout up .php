<?php
session_start();

// Check if the user is logged in and retrieve the user role from the session
if (!isset($_SESSION['user_role'])) {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}

$user_role = $_SESSION['user_role'];

// Define allowed roles for this page
$allowed_roles = ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'];

// Check if the user has the required role
if (!in_array($user_role, $allowed_roles)) {
    // Set a flag to indicate restricted access
    $restricted_access = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/user_mgt/user_management.css">
    <title>Admin Dashboard</title>
    <style>
        <?php if (isset($restricted_access) && $restricted_access): ?>

            /* Disable pointer events to block mouse interaction */
            body {
                pointer-events: none;
            }

        <?php endif;
        ?>
    </style>
    <!-- Add your other meta tags, styles, and scripts here -->
    <script>
        <?php if (isset($restricted_access) && $restricted_access): ?>
            // Disable all click events to block mouse interaction
            document.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
            }, true);
        <?php endif; ?>
    </script>
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
                <!-- <a href="../contact/contact.php">
                    <span class="fa fa-address-card">
                    </span>
                    <h3>Contact</h3>
                </a> -->
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
                <h1 class="h1">User Management</h1>
                <!-- Analyses -->
                <div class="analyse">
                    <div class="sales" onclick="window.location.href='../user_mgt/user.php';" style="cursor: pointer;">
                        <div class="status">
                            <div class="info">
                                <h1>Create User</h1>
                            </div>
                            <div>
                                <img src="../images/usermgt/adduser.png">
                            </div>
                        </div>
                    </div>
                    <div class="visits" onclick="window.location.href='../user_mgt/search_user.php';"
                        style="cursor: pointer;">
                        <div class="status">
                            <div class="info">
                                <h2>Assign</h2>
                                <h1>Function</h1>
                            </div>
                            <div>
                                <img src="../images/usermgt/usermgt.png">
                            </div>
                        </div>
                    </div>
                    <div class="visits" onclick="window.location.href='../user_mgt/showuser.php';"
                        style="cursor: pointer;">
                        <div class="status">
                            <div class="info">
                                <h2>Show</h2>
                                <h1>Show User</h1>
                            </div>
                            <div>
                                <img src="../images/usermgt/usermgt.png">
                            </div>
                        </div>
                    </div>
                </div>
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
                    <img src="../images/logo/logo.jpg">
                    <h2>FTB Bank</h2>
                    <p>Welcome to FTB Bank</p>
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