<?php
require '../vendor/autoload.php';

$host = "localhost";
$user = "root";
$pass = "";
$db = "demo";

// Create a connection to the database
$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $allowedExtensions = ['pdf', 'txt', 'doc', 'docx', 'png', 'jpg', 'jpeg', 'gif', 'xlsx', 'xls'];
    $allowedMimeTypes = [
        'application/pdf',
        'text/plain',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/png',
        'image/jpeg',
        'image/gif',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel',
    ];
    $filename = $_FILES['file1']['name'];
    $fileTmpName = $_FILES['file1']['tmp_name'];
    $fileExt = pathinfo($filename, PATHINFO_EXTENSION);

    if (in_array($fileExt, $allowedExtensions) || $fileExt == 'txt') {
        $uploadDirectory = 'uploads/';

        if (!is_dir($uploadDirectory)) {
            if (!mkdir($uploadDirectory, 0777, true)) {
                die('Failed to create the "uploads" directory.');
            }
        }

        $sql = 'SELECT MAX(id) as id FROM tbl_files';
        $result = mysqli_query($con, $sql);
        $newFilename = '';

        if ($result && $result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            $newFilename = $filename;
        } else {
            $newFilename = '1-' . $filename;
        }

        $filePath = $uploadDirectory . $newFilename;

        if (move_uploaded_file($fileTmpName, $filePath)) {
            $created = date('Y-m-d H:i:s'); // Changed the date format
            $shortDescription = $_POST['short_description']; // Provide a short description if needed
            $title = $_POST['title']; // Provide a title if needed
            $drop_file = $_POST['drop_file'];

            $sql = "INSERT INTO tbl_files (filename, created, drop_file, short_description, title) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $newFilename, $created, $drop_file, $shortDescription, $title);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: multi_upload.php?st=success");
                exit;
            } else {
                echo "Error: " . mysqli_error($con);
                header("Location: multi_upload.php?st=error");
                exit;
            }
        } else {
            header("Location: multi_upload.php?st=error");
            exit;
        }
    } else {
        header("Location: multi_upload.php?st=error");
        exit;
    }
}

if (isset($_GET['delete'])) {
    $fileToDelete = $_GET['delete'];
    $filePath = 'uploads/' . $fileToDelete;

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $sql = "DELETE FROM tbl_files WHERE filename = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $fileToDelete);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: multi_upload.php?st=deleted");
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
        header("Location: multi_upload.php?st=error");
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta content="width=device-width, initial-scale=1.0" name="viewport"> -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" type="text/css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Admin Dashboard/styles/multi_upload.css">
    <title>Admin Dashboard</title>
</head>

<body>

    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo">
                    <img src="../Admin Dashboard/images/profile.jpg">
                    <h2>FTB <span class="danger">Bank</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">
                        close
                    </span>
                </div>
            </div>

            <div class="sidebar">
                <a href="../Admin Dashboard/admin.php">
                    <span class="material-icons-sharp">
                        dashboard
                    </span>
                    <h3>Dashboard</h3>
                </a>
                <!-- <a href="../templates/createuser.php">
                    <span class="fa fa-user-circle-o">
                    </span>
                    <h3>Create User</h3>
                </a> -->
                <a href="../templates/search.php">
                    <span class="fa fa-search">
                    </span>
                    <h3>Search</h3>
                </a>

                <!-- <a href="../templates/input_data.php">
                    <span class="material-icons-sharp">
                        insights
                    </span>
                    <h3>Analytics</h3>
                </a> -->
                <a href="../templates/email.php">
                    <span class="fa fa-address-card">
                    </span>
                    <h3>Contact</h3>
                </a>
                <a href="../templates/multi_upload.php" class="active">
                    <span class="fa fa-upload">
                    </span>
                    <h3>Data Store</h3>
                </a>
                <a href="../templates/list_upload.php">
                    <span class="material-icons-sharp">
                        inventory
                    </span>
                    <h3>View File</h3>
                </a>
                <a href="../templates/assessment.php">
                    <span class="fa fa-address-book">
                        <!-- fab fa-app-store-ios -->
                    </span>
                    <h3>Assessment</h3>
                </a>
                <!-- <a href="../templates/query_tb.php">
                    <span class="material-icons-sharp">
                        report_gmailerrorred
                    </span>
                    <h3>Query Data</h3>
                </a> -->

                <a href="../templates/user_management.php">
                    <span class="fa fa-user-circle">
                    </span>
                    <h3>User Mgt</h3>
                </a>
                <a href="../templates/todo_management.php">
                    <span class="fa fa-list-alt">
                    </span>
                    <h3>To-do List</h3>
                </a>
                <a href="../templates/stock_management.php">
                    <span class="fa fa-briefcase">
                    </span>
                    <h3>Stock Mgt</h3>
                </a>


                <a href="../templates/logout.php">
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

            <div class="container2">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-header">
                                Input file for Upload
                            </div>
                            <form action="input_upload.php" method="post" enctype="multipart/form-data">
                                <button type="submit" name="action" value="upload" class="btn btn-info">Go to Input For
                                    Upload</button>
                            </form>


                            <div class="card-body">
                                <form action="multi_upload.php" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="file1">Select File to Upload:</label>
                                        <input type="file" name="file1" class="form-control-file" id="file1">
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Title:</label>
                                        <input type="text" name="title" class="form-control" id="title">
                                    </div>
                                    <div class="form-list">
                                        <label for="drop_file">Select:</label>
                                        <select name="drop_file" class="form-control" id="drop_file">
                                            <option value="Team Card Payment">Team Card Payment</option>
                                            <option value="Team ATM">Team ATM</option>
                                            <option value="Team Digital Branch">Team Digital Branch</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="short_description">Short Description:</label>
                                        <input type="text" name="short_description" class="form-control"
                                            id="short_description">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="submit" value="Save" class="btn btn-info">
                                    </div>
                                </form>

                                <?php if (isset($_GET['st'])) { ?>
                                    <div
                                        class="alert alert-<?php echo ($_GET['st'] == 'success') ? 'success' : 'danger'; ?> text-center">
                                        <?php echo ($_GET['st'] == 'success') ? 'File Uploaded Successfully!' : 'Invalid File Extension!'; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                        <img src="../Admin Dashboard/images/profile.jpg">
                    </div>
                </div>

            </div>
            <!-- End of Nav -->

            <div class="user-profile">
                <div class="logo">
                    <img src="../Admin Dashboard/images/profile.jpg">
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
    <script src="../Admin Dashboard/index.js"></script>
</body>

</html>