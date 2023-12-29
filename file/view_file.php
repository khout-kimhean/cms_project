<?php
include '../dashboard/check_access.php';
include '../connect/role_access.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get logged-in user's type
function getLoggedInUserType($conn, $userId)
{
    $userSql = "SELECT user_type FROM login_register WHERE id = ?";
    $userStmt = $conn->prepare($userSql);

    if (!$userStmt) {
        die("Prepare failed: " . $conn->error);
    }

    $userStmt->bind_param("i", $userId);

    if (!$userStmt->execute()) {
        die("Execute failed: " . $userStmt->error);
    }

    $userResult = $userStmt->get_result();

    if (!$userResult) {
        die("Get result failed: " . $userStmt->error);
    }

    if ($userResult->num_rows > 0) {
        $userData = $userResult->fetch_assoc();
        return $userData['user_type'];
    } else {
        return false; // User not found
    }
}

// Function to get logged-in user's name
function getLoggedInUserName($conn, $userId)
{
    $userSql = "SELECT name FROM login_register WHERE id = ?";
    $userStmt = $conn->prepare($userSql);

    if (!$userStmt) {
        die("Prepare failed: " . $conn->error);
    }

    $userStmt->bind_param("i", $userId);

    if (!$userStmt->execute()) {
        die("Execute failed: " . $userStmt->error);
    }

    $userResult = $userStmt->get_result();

    if (!$userResult) {
        die("Get result failed: " . $userStmt->error);
    }

    if ($userResult->num_rows > 0) {
        $userData = $userResult->fetch_assoc();
        return $userData['name'];
    } else {
        return false; // User not found
    }
}

// Get logged-in user's type
$loggedInUserId = $_SESSION['user_id'];
$loggedInUserType = getLoggedInUserType($conn, $loggedInUserId);

$sql = "SELECT * FROM upload_file";

// If the user is an admin, show all files
if ($loggedInUserType == 'admin') {
    $result = $conn->prepare($sql);
} else {
    // If user_type is set and not empty, filter by user_type
    if (!empty($loggedInUserType)) {
        $sql .= " WHERE user_type = ?";
    }

    $result = $conn->prepare($sql);

    // If user_type is set and not empty, bind the parameter
    if (!empty($loggedInUserType)) {
        $result->bind_param("s", $loggedInUserType);
    }
}

$result->execute();
$result = $result->get_result();

$searchResults = array();

if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];

    // Use a prepared statement to prevent SQL injection
    $sql = "SELECT * FROM upload_file WHERE (filename LIKE ? OR description LIKE ? OR title LIKE ?)";

    // If the user is not an admin, add user_type condition to the search query
    if ($loggedInUserType != 'admin') {
        $sql .= " AND user_type = ?";
    }

    $stmt = $conn->prepare($sql);

    // Add wildcard characters to the search pattern
    $searchPattern = "%" . $searchTerm . "%";

    // Bind parameters, including the user type condition if applicable
    if ($loggedInUserType != 'admin') {
        $stmt->bind_param("ssss", $searchPattern, $searchPattern, $searchPattern, $loggedInUserType);
    } else {
        $stmt->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
    }

    // Execute the query
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Check if there are any results
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }

    // Close the statement
    $stmt->close();
}



// Delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    // Check if the user is logged in
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        $idToDelete = $_GET['delete'];
        $loggedInUserId = $_SESSION['user_id'];

        // Get the logged-in user's name
        $loggedInUser = getLoggedInUserName($conn, $loggedInUserId);

        if ($loggedInUser !== false) {
            // Retrieve file details before deleting
            $fileDetailsSql = "SELECT filename, title,  description ,user_type FROM upload_file WHERE id = ?";
            $fileDetailsStmt = $conn->prepare($fileDetailsSql);

            if (!$fileDetailsStmt) {
                die("Prepare failed: " . $conn->error);
            }

            $fileDetailsStmt->bind_param("i", $idToDelete);

            if (!$fileDetailsStmt->execute()) {
                die("Execute failed: " . $fileDetailsStmt->error);
            }

            $fileDetailsResult = $fileDetailsStmt->get_result();

            if (!$fileDetailsResult) {
                die("Get result failed: " . $fileDetailsStmt->error);
            }

            if ($fileDetailsResult->num_rows > 0) {
                $fileDetails = $fileDetailsResult->fetch_assoc();

                // Insert into recover_file table
                $recoverSql = "INSERT INTO recover_file (filename, title,  description, user_type, delete_by) VALUES (?,  ?, ?, ?, ?)";
                $recoverStmt = $conn->prepare($recoverSql);

                if (!$recoverStmt) {
                    die("Prepare failed: " . $conn->error);
                }

                $recoverStmt->bind_param("sssss", $fileDetails['filename'], $fileDetails['title'],  $fileDetails['description'] , $fileDetails['user_type'], $loggedInUser);

                if (!$recoverStmt->execute()) {
                    die("Execute failed: " . $recoverStmt->error);
                }

                // Now, delete the file from upload_file table
                $deleteSql = "DELETE FROM upload_file WHERE id = ?";
                $deleteStmt = $conn->prepare($deleteSql);

                if (!$deleteStmt) {
                    die("Prepare failed: " . $conn->error);
                }

                $deleteStmt->bind_param("i", $idToDelete);

                if ($deleteStmt->execute()) {
                    header("Location: view_file.php?st=delete-success");
                    exit;
                } else {
                    echo "Deletion Error: " . $deleteStmt->error;
                }
            } else {
                echo "No file details found for ID: $idToDelete";
            }
        } else {
            echo "Error: Logged-in user not found.";
        }
    } else {
        // Handle the case where the user is not logged in
        echo "Error: User not logged in.";
    }
}


// Edit action
if (isset($_POST['edit']) && is_numeric($_POST['edit_id'])) {
    $editId = $_POST['edit_id'];
    $newTitle = $_POST['new_title'];
    $newdescription = strip_tags($_POST['new_description']);
    $newdescription = strip_tags($_POST['new_description']);

    $editSql = "UPDATE upload_file SET title = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($editSql);
    $stmt->bind_param("ssi", $newTitle, $newdescription, $editId);

    if ($stmt->execute()) {
        header("Location: view_file.php?st=edit-success");
        exit;
    } else {
        header("Location: view_file.php?st=edit-error");
        exit;
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" type="text/css"> -->
    <link rel="stylesheet" type="text/css" href="../styles/file/view_file.css">
    <!-- <link rel="stylesheet" type="text/css" href="../Admin Dashboard/styles/search.css"> -->
    <title>Responsive Dashboard</title>
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
            <div class="box">
                <div class="back_button">
                    <a href="../file/file_mgt.php" class="back-button">
                        <i class="fa fa-chevron-circle-left" style="font-size: 25px">Back</i>
                    </a>
                </div>
                <!-- <h2>Data List</h2> -->
                <div class="container2">
                    <div class="search">
                        <form action="view_file.php" method="post">
                            <div class="form-group">
                                <label for="searchTerm">Type here for search : </label>
                                <input type="text" name="searchTerm" class="form-control" id="searchTerm">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="search" value="Search" class="btn btn-info">
                            </div>
                        </form>
                    </div>
                    <!-- <div class="row"> -->
                    <div class="table-container">
                        <div class="col-md-8 offset-md-2">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>File Name</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Upload By</th>
                                        <th>View File Upload</th>
                                        <th>View data</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($searchResults)): ?>
                                    <?php
                                        $i = 1; // Initialize the ID counter to 1
                                        foreach ($searchResults as $row):
                                            ?>
                                    <tr>
                                        <td>
                                            <?php echo $i; // Display the ID starting from 1 ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['filename']); ?>
                                        </td>
                                        <td title="<?php echo htmlspecialchars($row['title']); ?>">
                                            <?php
                                                    $title = htmlspecialchars($row['title']);
                                                    echo strlen($title) > 18 ? substr($title, 0, 40) . '...' : $title;
                                                    ?>
                                        </td>
                                        <td title="<?php echo htmlspecialchars($row['description']); ?>">
                                            <?php
                                                    $description = htmlspecialchars($row['description']);
                                                    echo strlen($description) > 20 ? substr($description, 0, 20) . '...' : $description;
                                                    ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['user_type']); ?>
                                        </td>
                                        <td><a href="../file/view.php?file=<?php echo $row['filename']; ?>"
                                                target="_blank">View File</a></td>
                                        <td><a href="../file/view_data.php?id=<?php echo $row['id']; ?>">View Data</a>
                                        </td>
                                        <td><a href="../file/edit_data.php?id=<?php echo $row['id']; ?>">Edit</a>
                                        </td>
                                        <td><a href="../file/view_file.php?delete=<?php echo $row['id']; ?>">Delete</a>
                                        </td>
                                    </tr>
                                    <?php
                                            $i++; // Increment the ID counter for the next row
                                        endforeach;
                                        ?>
                                    <?php else: ?>
                                    <?php if ($result->num_rows > 0): ?>
                                    <?php
                                            $i = 1; // Initialize the ID counter to 1
                                            while ($row = $result->fetch_assoc()):
                                                ?>
                                    <tr>
                                        <td>
                                            <?php echo $i; // Display the ID starting from 1 ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['filename']); ?>
                                        </td>
                                        <td title="<?php echo htmlspecialchars($row['title']); ?>">
                                            <?php
                                                        $title = htmlspecialchars($row['title']);
                                                        echo strlen($title) > 18 ? substr($title, 0, 40) . '...' : $title;
                                                        ?>
                                        </td>
                                        <td title="<?php echo htmlspecialchars($row['description']); ?>">
                                            <?php
                                                        $description = htmlspecialchars($row['description']);
                                                        echo strlen($description) > 20 ? substr($description, 0, 20) . '...' : $description;
                                                        ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['user_type']); ?>
                                        </td>
                                        <td><a href="../file/view.php?file=<?php echo $row['filename']; ?>"
                                                target="_blank">View File</a></td>
                                        <td><a href="../file/view_data.php?id=<?php echo $row['id']; ?>">View Data</a>
                                        </td>
                                        <td><a href="../file/edit_data.php?id=<?php echo $row['id']; ?>">Edit</a>
                                        </td>
                                        <td><a href="../file/view_file.php?delete=<?php echo $row['id']; ?>">Delete</a>
                                        </td>
                                    </tr>
                                    <?php
                                                $i++; // Increment the ID counter for the next row
                                            endwhile;
                                            ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan='7'>No files found.</td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </tbody>



                            </table>
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