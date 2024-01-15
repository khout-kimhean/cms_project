<?php
include '../connect/conectdb.php';
include '../connect/role_access.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if the delete parameter is set
if (isset($_GET['delete'])) {
    $idToDelete = intval($_GET['delete']); // Convert to integer to prevent SQL Injection

    // Prepare the DELETE statement
    $deleteSql = "DELETE FROM user_move WHERE id = ?";
    $stmt = $con->prepare($deleteSql);

    if ($stmt === false) {
        die("Error preparing statement: " . $con->error);
    }

    $stmt->bind_param("i", $idToDelete);

    if ($stmt->execute()) {
        // Redirect on successful deletion
        header("Location: notification.php?delete-success");
        exit;
    } else {
        // Error handling
        echo "Error deleting record: " . $con->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/notification/notification.css">
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
                <a href="./dashboard.php">
                    <span class="material-icons-sharp">
                        dashboard
                    </span>
                    <h3>Dashboard</h3>
                </a>

                <a href="../file/file_mgt.php" <?php echo isLinkDisabled('file_mgt.php'); ?>>
                    <span class="fa fa-upload">
                    </span>
                    <h3>Store File</h3>
                </a>

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
                    <a href="../dashboard/dashboard.php" class="back-button">
                        <i class="fa fa-chevron-circle-left" style="font-size: 25px">Back</i>
                    </a>
                </div>
                <div class="notification-container">
                    <?php

                    // $sql = "UPDATE move_back SET number =1 WHERE number =0";
                    $result = mysqli_query($con, $sql);

                    $currentDate = date("Y-m-d");

                    $sql = "SELECT * FROM user_move WHERE end_date <= '$currentDate' AND end_date IS NOT NULL ORDER BY end_date DESC";
                    $result = mysqli_query($con, $sql);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            if (!empty($row['request_date']) && !empty($row['end_date'])) {
                                ?>
                    <div class="notify-alert-box">
                        <img src="../images/logo/logo.jpg">
                        <div class="notification-item">
                            <p>
                                <strong>
                                    <?php echo htmlspecialchars($row['display_name']); ?>
                                </strong><br><br>
                                <span>
                                    <?php echo htmlspecialchars($row['branch']); ?>
                                </span>
                            </p>
                        </div>
                        <div class="detail-item">
                            <div class="move-back">
                                <a href="../dashboard/notification.php?delete=<?php echo $row['id']; ?>"><i
                                        class="fa fa-arrow-circle-right" aria-hidden="true"></i> Move Back</a>
                            </div>
                            <?php echo htmlspecialchars($row['end_date']); ?>
                        </div>
                    </div>
                    <?php
                            }
                        }
                    } else {
                        echo "<p>No user found for the current date.</p>";
                    }

                    $sql = "UPDATE user_move SET number =1 WHERE number =0";
                    $result = mysqli_query($con, $sql);

                    $sql = "SELECT * FROM user_move ORDER BY id DESC";
                    $result = mysqli_query($con, $sql);
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            if (!empty($row['request_date']) && !empty($row['end_date'])) {
                                ?>
                    <div class="notify-alert-box">
                        <img src="../images/logo/logo.jpg">
                        <div class="notification-item">
                            <p>
                                <strong>
                                    <?php echo ($row['display_name']); ?>
                                </strong><br><br>
                                <span>
                                    <?php echo htmlspecialchars($row['branch']); ?>
                                </span>
                            </p>
                        </div>
                        <div class="date">
                            <?php echo htmlspecialchars($row['request_date']); ?>
                        </div>
                    </div>
                    <?php }
                        }
                    }
                    $sql = "SELECT * FROM user_new ORDER BY id DESC";
                    $result = mysqli_query($con, $sql);
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                    <div class="notify-alert-box">
                        <img src="../images/logo/logo.jpg">
                        <div class="notification-item">
                            <p>
                                <strong>
                                    <?php echo ($row['display_name']); ?>
                                </strong><br><br>
                                <span>
                                    <?php echo htmlspecialchars($row['branch']); ?>
                                </span>
                            </p>
                        </div>
                        <div class="date">
                            <?php echo htmlspecialchars($row['request_date']); ?>
                        </div>
                    </div>
                    <?php }
                    }

                    ?>
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



        </div>
    </div>
    <script src="../script/role_check.js"></script>
    <!-- <script src="orders.js"></script> -->
    <script src="../script/index.js"></script>
</body>

</html>