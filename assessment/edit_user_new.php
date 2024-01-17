<?php
include '../dashboard/check_access.php';
require '../vendor/autoload.php';
include '../connect/role_access.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

$alertType = "";
$alertMessage = "";

// Initialize variables
$id = "";
$request_no = "";
$display_name = "";
$application = "";
$branch = "";
$department = "";
$position = "";
$function = "";
$role = "";
$requester = "";
$approver = "";
$request_date = "";
$comment = "";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = array();

$sql = "SELECT * FROM user_new";
$result = $conn->query($sql);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $request_no = $_POST['request_no'];
    $display_name = $_POST['display_name'];
    $application = $_POST['application'];
    $branch = $_POST['branch'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $function = $_POST['function'];
    $role = $_POST['role'];
    $requester = $_POST['requester'];
    $approver = $_POST['approver'];
    $request_date = $_POST['request_date'];
    $comment = $_POST['comment'];

    // Prepare and execute the SQL statement
    $sql = "UPDATE user_new SET request_no = ?, display_name = ?, application = ?, branch = ?, department = ?, position = ?, function = ?, role = ?, requester = ?, approver = ?, request_date = ?, comment = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $alertType = "danger";
        $alertMessage = "Error preparing SQL statement: " . $conn->error;
    } else {
        $stmt->bind_param("ssssssssssssi", $request_no, $display_name, $application, $branch, $department, $position, $function, $role, $requester, $approver, $request_date, $comment, $id);

        if ($stmt->execute()) {
            $alertType = "success";
            $alertMessage = "User updated successfully.";
        } else {
            $alertType = "danger";
            $alertMessage = "Error updating user: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Retrieve user data for editing
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM user_new WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $alertType = "danger";
        $alertMessage = "Error preparing SQL statement: " . $conn->error;
    } else {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $request_no = $row['request_no'];
                $display_name = $row['display_name'];
                $application = $row['application'];
                $branch = $row['branch'];
                $department = $row['department'];
                $position = $row['position'];
                $function = $row['function'];
                $role = $row['role'];
                $requester = $row['requester'];
                $approver = $row['approver'];
                $request_date = $row['request_date'];
                $comment = $row['comment'];
            } else {
                $alertType = "warning";
                $alertMessage = "User not found.";
            }
        } else {
            $alertType = "danger";
            $alertMessage = "Error executing the SQL query: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/assessment/edit_user.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>

<body>

    <div class="container">
        <aside>
            <div class="toggle">
                <div class="logo">
                    <img src="../images/logo/logo.jpg">
                    <!-- <h2>FTB <span class="danger">Bank</span></h2> -->
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
                    <h3>Documents</h3>
                </a>
                <a href="../assessment/assessment.php" <?php echo isLinkDisabled('assessment.php'); ?> class="active">
                    <span class="fa fa-address-book">
                        <!-- fab fa-app-store-ios -->
                    </span>
                    <h3>IB User Assessment</h3>
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
                    <a href="../assessment/list_new_user.php" class="back-button">
                        <i class="fa fa-chevron-circle-left" style="font-size: 28px">
                            <h1>Back</h1>
                        </i>
                    </a>
                </div>

                <h2>Edit User assessment</h2>

                <form method="post" action="">

                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                    <div class="select_role">
                        <div class="user">
                            <label class="label_input" for="request_no">Request_no</label>
                            <input type="text" name="request_no" required placeholder="request_no"
                                value="<?php echo htmlspecialchars($request_no); ?>">
                            <label class="label_input" for="display_name">Request_for</label>
                            <input type="text" name="display_name" required placeholder="display_name"
                                value="<?php echo htmlspecialchars($display_name); ?>">

                        </div>

                        <div class="user">
                            <label class="label_input" for="application">Application</label>
                            <input type="text" name="application" required placeholder="application"
                                value="<?php echo htmlspecialchars($application); ?>">
                            <label class="label_input" for="branch">Branch</label>
                            <input type="text" name="branch" required placeholder="branch"
                                value="<?php echo htmlspecialchars($branch); ?>">
                        </div>
                        <div class="user">
                            <label class="label_input" for="department">Department</label>
                            <input type="text" name="department" required placeholder="department"
                                value="<?php echo htmlspecialchars($department); ?>">
                            <label class="label_input" for="position">Position</label>
                            <input type="text" name="position" required placeholder="position"
                                value="<?php echo htmlspecialchars($position); ?>">
                        </div>
                        <div class="user">
                            <label class="label_input" for="function">Function</label>
                            <input type="text" name="function" required placeholder="function"
                                value="<?php echo htmlspecialchars($function); ?>">
                            <label class="label_input" for="role">Role</label>
                            <input type="text" name="role" required placeholder="role"
                                value="<?php echo htmlspecialchars($role); ?>">

                        </div>
                        <div class="user">
                            <label class="label_input" for="requester">Requester</label>
                            <input type="text" name="requester" required placeholder="requester"
                                value="<?php echo htmlspecialchars($requester); ?>">
                            <label class="label_input" for="approver">Approver</label>
                            <input type="text" name="approver" required placeholder="approver"
                                value="<?php echo htmlspecialchars($approver); ?>">

                        </div>
                        <div class="user">
                            <label class="label_input" for="request_date">Request_date</label>
                            <input type="text" name="request_date" required placeholder="request_date"
                                value="<?php echo htmlspecialchars($request_date); ?>">

                        </div>
                        <div class="user1">
                            <label class="label_input" for="comment">Comment</label>
                            <input type="text" name="comment" required placeholder="Comment"
                                value="<?php echo htmlspecialchars($comment); ?>">
                        </div>

                    </div>


                    <input type="submit" name="submit" value="Save" class="form-btn">
                    <?php if ($alertMessage !== ""): ?>
                        <div class="alert alert-<?php echo $alertType; ?>" role="alert">
                            <?php echo $alertMessage; ?>
                        </div>
                    <?php endif; ?>
                </form>
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