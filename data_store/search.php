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

$searchResults = array();

if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];

    $sql = "SELECT * FROM tbl_files WHERE filename LIKE ? OR drop_file LIKE ? OR title LIKE ? OR short_description LIKE ?";
    $stmt = mysqli_prepare($con, $sql);
    $searchPattern = "%" . $searchTerm . "%";
    mysqli_stmt_bind_param($stmt, "ssss", $searchPattern, $searchPattern, $searchPattern, $searchPattern);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/data_store/search.css">
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
                <a href="../dashboard/dashboard.php">
                    <span class="material-icons-sharp">
                        dashboard
                    </span>
                    <h3>Dashboard</h3>
                </a>

                <a href="../data_store/search.php" class="active">
                    <span class="fa fa-search">
                    </span>
                    <h3>Search</h3>
                </a>
                <a href="../contact/contact.php">
                    <span class="fa fa-address-card">
                    </span>
                    <h3>Contact</h3>
                </a>
                <a href="../data_store/upload_file.php">
                    <span class="fa fa-upload">
                    </span>
                    <h3>Data Store</h3>
                </a>

                <a href="../data_store/list_upload.php">
                    <span class="material-icons-sharp">
                        inventory
                    </span>
                    <h3>View File</h3>
                </a>
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
                <a href="../data_store/data_mgt.php">
                    <span class="fa fa-briefcase">
                    </span>
                    <h3>Stock Mgt</h3>
                </a>


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
            <br />
            <div class="container2">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-header" class="back-button">

                                <span><a href="../dashboard/dashboard.php" class="back-button"><i
                                            class="fa fa-chevron-circle-left" style="font-size:28px"></i></a>
                                    <h2>Search by filename/drop_file/short_description/Title</h2>
                                </span>

                            </div>
                            <div class="card-body">
                                <form action="search.php" method="post">
                                    <div class="form-group">
                                        <label for="searchTerm">Type here for search : </label>
                                        <input type="text" name="searchTerm" class="form-control" id="searchTerm">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="search" value="Search" class="btn btn-info">
                                    </div>
                                </form>
                                <div class="table-container">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>File Name</th>
                                                <th>Team Drop FIle</th>
                                                <th>Title</th>
                                                <th>short_description</th>
                                                <th>View</th>
                                                <th>Option</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($searchResults)) {
                                                $i = 1;
                                                foreach ($searchResults as $row) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $i++; ?>
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
                                                        <td><a href="view.php?file=<?php echo $row['filename']; ?>"
                                                                target="_blank">View</a>
                                                        </td>
                                                        </td>
                                                        <td><a
                                                                href="search.php?delete=<?php echo $row['filename']; ?>">Delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>No matching files found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
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
                        <img src="../images/logo/logo.jpg">
                    </div>
                </div>

            </div>
            <!-- End of Nav -->

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
    <script src="../script/index.js""></script>
</body>

</html>