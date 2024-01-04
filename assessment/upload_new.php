<?php
require '../vendor/autoload.php';
include '../connect/conectdb.php';
include '../connect/role_access.php';

$alertType = "";
$alertMessage = "";
// preg_match("\s*(.*?)\s*(?:\\n\s*Office|$)/", $section, $branchMatch);
// preg_match("/Department: (.*)/", $section, $departmentMatch);
function extractDataFromPdfText($text)
{
    $extractedData = [];
    $sections = explode("\n\n", $text);
    foreach ($sections as $section) {
        preg_match("/Request No: (.*)/", $section, $requestNoMatch);
        preg_match("/Name: (.*)/", $section, $nameMatch);
        preg_match("/Branch\s*\/\s*Department\s*:\s*(.*)\s*-/", $section, $branchMatch);

        // Corrected this line to match the department correctly
        // preg_match("/Department:\s*(.*?)\s*(?:Request|Branch|$)/", $section, $departmentMatch); 
        preg_match("/Branch \/ Department\s*:\s*(.*)/", $text, $departmentMatch);

        preg_match("/Job title: (.*)/", $section, $positionMatch);
        preg_match("/Function Name: (.*)/", $section, $functionMatch);
        preg_match("/Role:\s*(.*)\s*\(/", $section, $roleMatch);
        preg_match("/Requester: (.*)/", $section, $requesterMatch);
        preg_match("/Review By Name: (.*)/", $section, $reviewer1Match);
        preg_match("/Duration: (.*)/", $section, $durationMatch);
        preg_match("/Comment: (.*)/", $section, $commentMatch);
        preg_match("/Date: (.*)/", $section, $requester_dateMatch);

        $extractedData[] = [
            'request_no' => $requestNoMatch[1] ?? '',
            'name' => $nameMatch[1] ?? '',
            'branch' => $branchMatch[0] ?? '',
            'department' => $departmentMatch[1] ?? '',
            'position' => $positionMatch[1] ?? '',
            'function' => $functionMatch[1] ?? '',
            'role' => $roleMatch[1] ?? 'Supervisor Role (Checker)',
            'requester' => $requesterMatch[1] ?? '',
            'checker' => $durationMatch[1] ?? '',
            'reviewer1' => $reviewer1Match[1] ?? '',
            'reviewer2' => $requesterMatch[1] ?? '',
            'approver' => $durationMatch[1] ?? '',
            'comment' => $commentMatch[1] ?? '',
            'request_date' => $requester_dateMatch[1] ?? '',
        ];

    }
    return $extractedData;
}




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $fileName = $_FILES['file']['name'];
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!empty($fileName) && $fileExtension === 'pdf') {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($fileTmpPath);
        $text = $pdf->getText();

        $data = extractDataFromPdfText($text);
        $sql = "INSERT INTO user_create (request_no, name, branch, department, position, function, role, requester, checker, reviewer1, reviewer2, approver, comment, request_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "Error preparing SQL statement: " . $conn->error;
            exit;
        }

        foreach ($data as $row) {
            $request_no = $row['request_no'];
            $name = $row['name'];
            $branch = $row['branch'];
            $department = $row['department'];
            $position = $row['position'];
            $function = $row['function'];
            $role = $row['role'];
            $requester = $row['requester'];
            $checker = $row['checker'];
            $reviewer1 = $row['reviewer1'];
            $reviewer2 = $row['reviewer2'];
            $approver = $row['approver'];
            $comment = $row['comment'];
            $request_date = $row['request_date'];

            $stmt->bind_param("ssssssssssssss", $request_no, $name, $branch, $department, $position, $function, $role, $requester, $checker, $reviewer1, $reviewer2, $approver, $comment, $request_date);

            if (!$stmt->execute()) {
                echo "Error inserting data: " . $stmt->error;
                break;
            }
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Error: Only PDF files are allowed.";
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
                    <h2>FTB <span class="danger">Bank</span></h2>
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
                <a href="../file/file_mgt.php" <?php echo isLinkDisabled('file_mgt.php'); ?>>
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
                <a href="../assessment/assessment.php" <?php echo isLinkDisabled('assessment.php'); ?> class="active">
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
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main>
            <div class="container2">
                <a href="../assessment/assessment.php" class="back-button">
                    <i class="fa fa-chevron-circle-left" style="font-size: 28px;">Back</i>
                </a>
                <h2>Upload New User</h2>
                <!-- <div class="content">
                    <h2>Upload New User Or</h2>
                    <a href="../assessment/asscess_new_user.php">
                        <button class="input">Input Manual</button>
                    </a>
                </div> -->
                <form method="post" enctype="multipart/form-data" id="uploadForm" onsubmit="changeBackground()">
                    <label for="file">Select File to Upload:</label>
                    <input class="upload" type="file" name="file" id="file" accept=".xls, .xlsx">
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