<?php
// session_start();
require '../vendor/autoload.php';

// Include the file with the access check
include '../dashboard/check_access.php';
include '../connect/role_access.php';

$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'demo';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$alertType = "";
$alertMessage = "";
function extractDataFromPdfText($text)
{
    $extractedData = [];
    $sections = explode("PHP_EOL", $text);
    // echo $sections[0];
    foreach ($sections as $section) {
        // ... your existing preg_match calls ... 
        preg_match("/Request No: (.*)/", $section, $requestNoMatch);
        preg_match("/Name: (.*)/", $section, $nameMatch);
        preg_match("/Branch: (.*?)(?=(?:\w+ :|$))/s", $section, $branchMatch);
        preg_match("/Branch \/ Department\s*:\s*(.*?)(?=\s*Branch:)/s", $text, $departmentMatch);
        preg_match("/Job title: \s*(.*?)\s*Phone No:/s", $text, $positionMatch);
        preg_match("/Switching (.*)/", $section, $applicationMatch);
        preg_match("/Application\s+(.*?)\s+:/", $section, $roleMatch);
        preg_match("/Switching (.*)/", $section, $functionMatch);
        preg_match("/Request By\s*(.*?)\s*Check By/s", $text, $requester_dateMatch);
        preg_match("/Description: \s*(.*?)\s*Request By/s", $section, $commentMatch);


        $extractedData[] = [
            'request_no' => $requestNoMatch[1] ?? '',
            'display_name' => $nameMatch[1] ?? '',
            'branch' => substr($branchMatch[1], 0, 12) ?? '',
            'department' => $departmentMatch[1] ?? '',
            'position' => $positionMatch[1] ?? '',
            'application' => substr($applicationMatch[0], 0, 23) ?? '',
            'function' => substr($functionMatch[1], 37, 19) ?? '',
            'role' => $roleMatch[1] ?? '',
            'request_date' => $requester_dateMatch[1] ?? '',
            'comment' => $commentMatch[1] ?? '',

        ];
    }
    return $extractedData;
}

if (isset($_POST['submit'])) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!empty($fileName) && $fileExtension === 'pdf') {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($fileTmpPath);
        $text = $pdf->getText();

        $data = extractDataFromPdfText($text);
        $sql = "INSERT INTO user_new ( request_no, display_name, branch, department, position, application, function, role, requester, approver, request_date , comment , upload_date) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            foreach ($data as $row) {
                $request_no = $row['request_no'];
                $display_name = $row['display_name'];
                $branch = $row['branch'];
                $department = $row['department'];
                $position = $row['position'];
                $application = $row['application'];
                $function = isset($row['function']) ? $row['function'] : '';
                $function = str_replace([')', '(', ':'], '', $function);

                $role = $row['role'];
                $requester = $_POST['requester'];
                $approver = $_POST['approver'];
                $request_date = $row['request_date'];
                $comment = $row['comment'];

                $stmt->bind_param("ssssssssssss", $request_no, $display_name, $branch, $department, $position, $application, $function, $role, $requester, $approver, $request_date, $comment);
                if (!$stmt->execute()) {
                    echo "Error inserting data: " . $stmt->error;
                    break;
                }
            }

            $stmt->close();

            // Redirect to another page to avoid form resubmission
            header("Location: upload_new.php");
            exit;
        } else {
            echo "Error preparing SQL statement: " . $conn->error;
        }
    }
}

if (isset($_POST['edit']) && is_numeric($_POST['edit'])) {
    $requester = $_POST['requester'];
    $approver = $_POST['approver'];

    $editSql = "UPDATE tbl_data_store SET requester = ?, approver = ? WHERE filename = ?";
    $stmt = $conn->prepare($editSql);
    $stmt->bind_param("ss", $requester, $approver);

    if ($stmt->execute()) {
        header("Location: upload_new.php?st=edit-success");
        exit;
    } else {
        header("Location: upload_new.php?st=edit-error");
        exit;
    }
}

$conn->close();

$error = array();

$sql = "SELECT * FROM login_register";
// $result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/assessment/upload.css">
    <title>Assessment</title>
</head>

<body>

    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo">
                    <img src="../images/logo/logo.jpg">
                    <!-- <h2>FTB <span class="danger">Bank</span></h2> -->
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">
                        close
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
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main>
            <div class="container2">
                <a href="../assessment/assessment.php" class="back-button">
                    <i class="fa fa-chevron-circle-left" style="font-size: 28px;">
                        <h1>Back</h1>
                    </i>
                </a>
                <h2>Upload New User</h2>
                <form method="post" enctype="multipart/form-data" id="uploadForm" onsubmit="changeBackground()">
                    <label for="file">Select File to Upload:</label>
                    <input class="upload" type="file" name="file" id="file">
                    <div class="input_content">
                        <input type="text" name="requester" required placeholder="Enter requester ">
                        <input type="text" name="approver" required placeholder="Enter Approver ">
                    </div>
                    <input class="submit" type="submit" name="submit" value="Upload File" id="uploadButton">
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

    <!-- <script src="orders.js"></script> -->
    <script src="../script/role_check.js"></script>
    <script src="../script/index.js"></script>
</body>

</html>