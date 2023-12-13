<?php
// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'demo';

// Create a new database connection for displaying users
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define an array to store errors
$error = array();

// Delete user if the delete button is clicked
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $delete_sql = "DELETE FROM login_register WHERE id = $user_id";
    if ($conn->query($delete_sql) === TRUE) {
        header('Location: admin.php'); // Redirect to refresh the user list
        exit();
    } else {
        $error[] = 'Error deleting user: ' . $conn->error;
    }
}

// Retrieve user data from the database
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
        <!-- Sidebar Section -->
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

                <a href="../user_mgt/user_management.php">
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

        <!-- Main Content -->
        <main>
            <h1>Analytics</h1>
            <!-- Analyses -->
            <div class="analyse">
                <div class="sales" onclick="window.location.href='#';" style="cursor: pointer;">
                    <div class="status">
                        <div class="info">
                            <h3>Total Sales</h3>
                            <h1>$70,000</h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                <p>+81%</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="visits" onclick="window.location.href='#';" style="cursor: pointer;">
                    <div class="status">
                        <div class="info">
                            <h3>Site Visit</h3>
                            <h1>9,854</h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                <p>-48%</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="searches" onclick="window.location.href='#';" style="cursor: pointer;">
                    <div class="status">
                        <div class="info">
                            <h3>Searches</h3>
                            <h1>14,147</h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                <p>+21%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="new-users">
                <h2>Option</h2>
                <div class="user-list">
                    <div class="user">
                        <a href="../chatbot/chat.html">
                            <img src="../images/background/chat.png" alt="ChatBot">
                            <h2>ChatBot</h2>
                            <p>User ChatBot Here</p>
                        </a>
                    </div>
                    <div class="user">
                        <a href="../data_store/datachat.php">
                            <!-- <i class="fa fa-upload" style="font-size:75px;color:blue"></i> -->
                            <img src="../images/file/file.png" alt="Upload File">
                            <h2>Data Chat</h2>
                            <p>input data for chat</p>
                        </a>
                    </div>
                    <div class="user">
                        <a href="#p">
                            <img src="../images/file/file1.png" alt="Show File">
                            <h2>Data Input</h2>
                            <p>Click here to view data</p>
                        </a>
                    </div>
                    <div class="user">
                        <a href="../find_error/read_file.php">
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
                        $userCount = 0; // Initialize a counter variable
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if ($userCount < 3) { // Display only 3 users
                                    echo '<tr>
                                        <td>' . $row['id'] . '</td>
                                        <td>' . $row['name'] . '</td>
                                        <td>' . $row['email'] . '</td>
                                        <td>' . $row['user_type'] . '</td>
                                        <td>
                                        <form method="post">
                                            <input type="hidden" name="user_id" value="' . $row['id'] . '">
                                            
                                        </form>
                                        </td>
                                    </tr>';
                                    $userCount++; // Increment the counter
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


                <a href="../user_mgt/showuser.php">Show All</a>
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
            <!-- End of Nav -->

            <div class="user-profile" onclick="window.location.href='https://ftb.com.kh/en/'">
                <div class="logo">
                    <img src="../images/logo/logo.jpg" alt="FTB Bank Logo">
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