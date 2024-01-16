<?php
require '../vendor/autoload.php';
include '../dashboard/check_access.php';
include '../connect/role_access.php';
$host = "localhost";
$user = "root";
$pass = "";
$db = "demo";

// Create a connection to the database
$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT * FROM login_register";
$result = mysqli_query($con, $sql);


$sql = "SELECT * FROM login_register";

$searchResults = array();

if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];

    $sql = "SELECT * FROM login_register WHERE name LIKE ?";
    $stmt = mysqli_prepare($con, $sql);
    $searchPattern = "%" . $searchTerm . "%";
    mysqli_stmt_bind_param($stmt, "s", $searchPattern);
    mysqli_stmt_execute($stmt);
    $stmt_result = $stmt->get_result(); // Use this line instead

    if ($stmt_result && mysqli_num_rows($stmt_result) > 0) {
        while ($row = mysqli_fetch_assoc($stmt_result)) {
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
    <link rel="stylesheet" type="text/css" href="../styles/user_mgt/user.css">
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

                <a href="../user_mgt/user_management.php" <?php echo isLinkDisabled('user_management.php'); ?>
                    class="active">
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
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main>
            <!-- <br /> -->
            <div class="container2">

                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <!-- <a href="../templates/user_management.php" class="back-button"><i
                                    class="fa fa-chevron-circle-left" style="font-size:32px"></i></a> -->
                            <a href="createuser.php">
                                <button>

                                    <h2>New User</h2>
                                </button>
                            </a>
                            <div class="card-header" class="back-button">
                                <span>

                                    <h2>Search User Name/Role</h2>
                                </span>
                            </div>
                            <div class="card-body">
                                <form action="user.php" method="post">
                                    <div class="form-group">
                                        <label for="searchTerm">Type here for search : </label>
                                        <input type="text" name="searchTerm" class="form-control" id="searchTerm"
                                            required placeholder="Search by name...">
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
                                                <th>User Name</th>
                                                <th>Email</th>
                                                <th>Type User</th>
                                                <!-- <th>Permission</th> -->
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
                                                            <?php echo htmlspecialchars($row['name']); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo htmlspecialchars($row['email']); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo htmlspecialchars($row['user_type']); ?>
                                                        </td>
                                                        <!-- <td><a class="click"
                                                                href="../user_mgt/assign_function.php?id=<?php echo $row['id']; ?>">Permission</a>
                                                        </td> -->
                                                        <td><a class="click1"
                                                                href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } elseif ($result->num_rows > 0) {
                                                $i = 1;
                                                while ($row = $result->fetch_assoc()) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $i++; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo htmlspecialchars($row['name']); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo htmlspecialchars($row['email']); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo htmlspecialchars($row['user_type']); ?>
                                                        </td>
                                                        <!-- <td><a class="click"
                                                                href="../user_mgt/assign_function.php?id=<?php echo $row['id']; ?>">Permission</a>
                                                        </td> -->
                                                        <td><a class="click1"
                                                                href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='6'>No matching users found.</td></tr>";
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