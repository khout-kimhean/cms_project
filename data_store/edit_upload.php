<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Handle file upload here
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
    $editId = $_POST['edit_id'];
    $newTitle = $_POST['new_title'];
    $newShortDescription = strip_tags($_POST['new_short_description']);

    $editSql = "UPDATE input_file SET title = ?, short_description = ? WHERE id = ?";
    $stmt = $conn->prepare($editSql);
    $stmt->bind_param("ssi", $newTitle, $newShortDescription, $editId);

    if ($stmt->execute()) {
        header("Location: edit_upload.php?st=edit-success");
        exit;
    } else {
        header("Location: edit_upload.php?st=edit-error");
        exit;
    }
}
?>

<script src="https://cdn.ckeditor.com/4.9.2/standard/ckeditor.js"></script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Admin Dashboard/styles/edit_upload.css">
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
                <a href="../Admin Dashboard/admin.php" class="active">
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
                <!-- <a href="#">
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
                <a href="../templates/multi_upload.php">
                    <span class="fa fa-upload">
                    </span>
                    <h3>Multi_Upload</h3>
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
                <a href="../templates/query_tb.php">
                    <span class="material-icons-sharp">
                        report_gmailerrorred
                    </span>
                    <h3>Query Data</h3>
                </a>
                <!-- <a href="#">
                    <span class="material-icons-sharp">
                        report_gmailerrorred
                    </span>
                    <h3>Reports</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp">
                        settings
                    </span>
                    <h3>Settings</h3>
                </a> -->
                <a href="../templates/login.php">
                    <span class="material-icons-sharp">
                        add
                    </span>
                    <h3>New Login</h3>
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
                <!-- <div class="back_button"><a href="../Admin Dashboard/admin.php" class="back-button"><i
                            class="fa fa-chevron-circle-left" style="font-size:28px"></i></a></div> -->
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-header">
                            Edit FIle
                            </div>
                            
                            <!-- <form action="multi_upload.php" method="post" enctype="multipart/form-data">
                                <button type="submit" name="action" value="upload" class="btn btn-info">Go Back To Upload File</button>
                            </form> -->
                            <div class="card-body">
                                <form action="input_upload.php" method="post" enctype="multipart/form-data">

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


                                    <textarea for="short_description" name="short_description"></textarea><br />

                                    <div class="form-group">
                                        <input type="submit" name="submit" value="Save Change" class="btn btn-info">
                                        <a href="../templates/view_upload.php" class="back-button"><i
                                            class="fa fa-chevron-circle-left" style="font-size:39px"></i></a>
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
            <script>
                // CKEDITOR.replace('editor1');  
                CKEDITOR.replace('short_description');

                function getData() {

                    var editor_data = CKEDITOR.instances['short_description'].getData();
                    // CKEDITOR.instances['short_description'].setData(short_description);
                }  
            </script>
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

    <script src="../Admin Dashboard/index.js"></script>
</body>

</html>