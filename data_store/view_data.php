<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$searchResults = array();

if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];

    $sql = "SELECT * FROM data_store WHERE short_description LIKE ? OR title LIKE ? OR drop_file LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchPattern = "%" . $searchTerm . "%";
    $stmt->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
}

// Delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    $deleteSql = "DELETE FROM data_store WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $idToDelete);

    if ($stmt->execute()) {
        header("Location: view_data.php?st=delete-success");
        exit;
    } else {
        header("Location: view_data.php?st=delete-error");
        exit;
    }
}

// Edit action
if (isset($_POST['edit']) && is_numeric($_POST['edit_id'])) {
    $editId = $_POST['edit_id'];
    $newTitle = $_POST['new_title'];
    $newShortDescription = strip_tags($_POST['new_short_description']);
    $newDescription = strip_tags($_POST['new_description']);

    $editSql = "UPDATE data_store SET title = ?, short_description = ? WHERE id = ?";
    $stmt = $conn->prepare($editSql);
    $stmt->bind_param("ssi", $newTitle, $newShortDescription, $editId);

    if ($stmt->execute()) {
        header("Location: view_data.php?st=edit-success");
        exit;
    } else {
        header("Location: view_data.php?st=edit-error");
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
    <link rel="stylesheet" type="text/css" href="../styles/data_store/view_data.css">
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
                <a href="../data_store/data_mgt.php" class="active">
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
                    <a href="../data_store/data_mgt.php" class="back-button">
                        <i class="fa fa-chevron-circle-left" style="font-size: 25px">Back</i>
                    </a>
                </div>
                <!-- <h2>Data List</h2> -->
                <div class="container2">
                    <div class="search">
                        <form action="view_data.php" method="post">
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
                                        <th>Team Drop FIle</th>
                                        <th>Title</th>
                                        <th>Short Description</th>
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
                                        <td>
                                            <?php echo htmlspecialchars($row['drop_file']); ?>
                                        </td>
                                        <td title="<?php echo htmlspecialchars($row['title']); ?>">
                                            <?php
                                                    $title = htmlspecialchars($row['title']);
                                                    echo strlen($title) > 18 ? substr($title, 0, 40) . '...' : $title;
                                                    ?>
                                        </td>
                                        <td title="<?php echo htmlspecialchars($row['short_description']); ?>">
                                            <?php
                                                    $shortDescription = htmlspecialchars($row['short_description']);
                                                    echo strlen($shortDescription) > 20 ? substr($shortDescription, 0, 20) . '...' : $shortDescription;
                                                    ?>
                                        </td>


                                        <td><a href="../data_store/view_file.php?file=<?php echo $row['filename']; ?>"
                                                target="_blank">View File Upload</a>
                                        </td>
                                        <td><a href="../data_store/view_1.php?id=<?php echo $row['id']; ?>">View
                                                Data</a>
                                        </td>



                                        <td>
                                            <a href="edit_data.php?id=<?php echo $row['id']; ?>">Edit Data</a>
                                        </td>
                                        <td>
                                            <a href="view_data.php?delete=<?php echo $row['id']; ?>">Delete Data</a>
                                        </td>
                                    </tr>
                                    <?php
                                            $i++; // Increment the ID counter for the next row
                                        endforeach;
                                        ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan='7'>No files found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>


                            </table>
                        </div>
                    </div>
                    <div class="note">
                        <h3>* if data have filename please click "View File Upload " and if don't have filename please
                            click"View Data " for view </h3>
                    </div>
                </div>
            </div>
        </main>
        <!-- <div class="right-section">
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
                        <p>Welcome, <b>
                                <KIM>
                        </p>
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
                            <h3>Workshop</h3>
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
                            <h3>Workshop</h3>
                            <small class="text_muted">
                                08:00 AM - :00 PM
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
        </div> -->
    </div>
    <script src="../script/index.js"></script>
</body>

</html>