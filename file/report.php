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
$alertType = ""; // Define the alert type (success or danger)
$alertMessage = "";

$sql = "SELECT * FROM recover_file";

if (isset($_POST['delete'])) {
    $filename = $_POST['filename'];
    $delete_sql = "DELETE FROM recover_file WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);

    if (!$stmt) {
        // Error preparing the statement
        $error[] = 'Error preparing statement: ' . $conn->error;
    } else {
        $stmt->bind_param('s', $filename);

        if ($stmt->execute()) {
            // Successful deletion
            $alertType = "success";
            $alertMessage = "File deleted successfully.";
            header('Location: report.php'); // Redirect to refresh the file list
            exit();
        } else {
            // Error deleting file
            $error[] = 'Error deleting file: ' . $stmt->error;
        }

        $stmt->close();
    }
}

if (isset($_POST['recover'])) {
    $filenameToRecover = $_POST['filename'];

    // Use prepared statement to prevent SQL injection
    $recoverQuery = $conn->prepare("SELECT * FROM recover_file WHERE id = ?");
    $recoverQuery->bind_param('s', $filenameToRecover);
    $recoverQuery->execute();

    $recoverResult = $recoverQuery->get_result();

    if ($recoverResult->num_rows > 0) {
        $recoverRow = $recoverResult->fetch_assoc();

        // Insert recovered data into upload_file table
        $insertQuery = $conn->prepare("INSERT INTO upload_file (id, filename, title, user_type, description) VALUES (?,  ?, ?, ?, ?)");
        $insertQuery->bind_param('sssss', $recoverRow['id'], $recoverRow['filename'], $recoverRow['title'], $recoverRow['user_type'], $recoverRow['description']);

        if ($insertQuery->execute()) {
            // File recovered successfully

            // Delete the recovered record from recover_file table
            $deleteQuery = $conn->prepare("DELETE FROM recover_file WHERE id = ?");
            $deleteQuery->bind_param('s', $filenameToRecover);
            $deleteQuery->execute();

            $alertType = 'success';
            $alertMessage = 'File recovered successfully.';
            header('Location: report.php');
            exit();
        } else {
            // Error recovering file
            $alertType = 'danger';
            $alertMessage = 'Error recovering file: ' . $conn->error;
        }

        $insertQuery->close();
    }

    $recoverQuery->close();
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/file/report.css">
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
                <!-- <a href="../contact/contact.php">
                    <span class="fa fa-address-card">
                    </span>
                    <h3>Contact</h3>
                </a> -->
                <a href="../file/file_mgt.php" <?php echo isLinkDisabled('file_mgt.php'); ?> class="active">
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
            <div class="container2">
                <div class="back_button">
                    <a href="../file/file_mgt.php" class="back-button">
                        <i class="fa fa-chevron-circle-left" style="font-size: 24px">Back</i>
                    </a>
                    <h2>Recover and Report</h2>
                </div>
                <div class="table-container">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Filename</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Upload By</th>
                                <th>Delete By</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $userCount = 0; // Initialize a counter variable
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $title = htmlspecialchars($row['title']);
                                    $shortenedTitle = strlen($title) > 18 ? substr($title, 0, 10) . '...' : $title;
                                    $description = htmlspecialchars($row['description']);
                                    $shortenedDescription = strlen($description) > 18 ? substr($description, 0, 10) . '...' : $description;
                                    echo '<tr>
                                        <td>' . ($userCount + 1) . '</td>
                                        <td>' . $row['filename'] . '</td>
                                        <td title="' . $title . '">' . $shortenedTitle . '</td>
                                        <td title="' . $description . '">' . $shortenedDescription . '</td>
                                        <td>' . $row['user_type'] . '</td>
                                        <td>' . $row['delete_by'] . '</td>
                                        <td>
                                            <form method="post">
                                                <input type="hidden" name="filename" value="' . $row['id'] . '">
                                                <input class="button1" type="submit" name="recover" value="Recover">
                                                <input class="button2" type="submit" name="delete" value="Delete">
                                            </form>
                                        </td>
                                    </tr>';
                                    $userCount++; // Increment the counter
                                }
                            } else {
                                echo "<tr><td colspan='5'>No users found.</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>

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