<?php
require '../vendor/autoload.php';
include '../connect/conectdb.php';
// Include the file with the access check
// include '../dashboard/check_access.php';
include '../connect/role_access.php';

$alertType = "";
$alertMessage = "";


function extractDataFromPdfText($text)
{
    $extractedData = [];
    $sections = explode("FILE_IGNORE_NEW_LINES", $text);
    // echo nl2br($sections[0]);

    foreach ($sections as $section) {
        // ... your existing preg_match calls ... 
        preg_match("/Request No: (.*)/", $section, $requestNoMatch);
        preg_match("/Name:\s*(.*)/", $section, $nameMatch);
        preg_match("/Branch:\s*(.*?)\s*Department:/s", $section, $branchMatch);
        preg_match("/Department:\s*(.*?(?:\s*Department\s*|$))/s", $section, $departmentMatch);
        preg_match("/Position:\s*(.*?)(?=\s*Application)/s", $section, $positionMatch);
        preg_match("/Switching\s*(.*?)\s*Application/", $section, $applicationMatch);
        preg_match("/Application\s+(.*?)\s+:/", $section, $roleMatch);
        preg_match("/Switching\s*(.*)/s", $section, $functionMatch);
        preg_match("/Move\s*To.*?Branch:\s*(.*?)(?:\s*Department:|$)/s", $section, $m_branchMatch);
        preg_match("/Move\s*To\s*Branch:\s*(.*?)(?:\s*Department:\s*(.*?)(?:\s*Department|$)|$)/s", $section, $m_departmentMatch);
        preg_match("/Move\s*To.*?Position:\s*(.*?)(?:\s*Application|$)/s", $section, $m_positionMatch);

        preg_match("/Move\s*To.*?Switching\s*Application\s*(.*)/s", $section, $m_functionMatch);
        preg_match("/Move\s*To.*?Switching\s*Application\s*(.*?)(?=\s*:|$)/s", $section, $m_roleMatch);
        // preg_match("/Request By (.*)/", $section, $requesterMatch);
        // preg_match("/approver: (.*)/", $section, $approverMatch);
        preg_match("/From\s*Date:\s*(.*?)\s*To\s*date/s", $section, $requester_dateMatch);
        preg_match("/To\s*date:\s*(.*?)\s*Move\s*/s", $section, $end_dateMatch);
        preg_match("/Duration\s*Move\s*:(.*)/", $section, $durationMatch);

        //   print_r($m_roleMatch);
        // echo $durationMatch[1];

        $extractedData[] = [
            'request_no' => $requestNoMatch[1] ?? '',
            'display_name' => $nameMatch[1] ?? '',
            'branch' => $branchMatch[1] ?? '',
            'department' => $departmentMatch[1] ?? '',
            'position' => $positionMatch[1] ?? '',
            'application' => substr($applicationMatch[0], 0, 23) ?? '',
            'function' => substr($functionMatch[1], 36, 14) ?? '',
            'role' => $roleMatch[1] ?? '',
            'm_branch' => $m_branchMatch[1] ?? '',
            'm_department' => $m_departmentMatch[2] . ' Department' ?? '',
            'm_position' => $m_positionMatch[1] ?? '',
            'm_function' => substr($m_functionMatch[1], 24, 9) ?? '',
            'm_role' => $m_roleMatch[1] ?? '',
            // 'requester' => $requesterMatch[1] ?? '',
            // 'approver' => $approverMatch[1] ?? '',
            'request_date' => $requester_dateMatch[1] ?? '',
            'end_date' => $end_dateMatch[1] ?? '',
            'duration' => substr($durationMatch[1], 0, 12) ?? '',
        ];
    }
    return $extractedData;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!empty($fileName) and $fileExtension === 'pdf') {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($fileTmpPath);
        $text = $pdf->getText();
        // echo nl2br($text);

        $data = extractDataFromPdfText($text);
        $sql = "INSERT INTO user_move (request_no, display_name, branch, department, position, application, function, role, m_branch, m_department, m_position, m_function, m_role, requester, duration, approver, request_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "Error preparing SQL statement: " . $conn->error;
            exit;
        }
        foreach ($data as $row) {
            $request_no = $row['request_no'];
            $fullname = $row['display_name'];
            $branch = $row['branch'];
            $department = $row['department'];
            $position = $row['position'];
            $application = $row['application'];
            $function = $row['function'];
            $role = $row['role'];
            $m_branch = $row['m_branch'];
            $m_department = $row['m_department'];
            $m_position = $row['m_position'];
            $m_function = $row['m_function'];
            $m_role = $row['m_role'];
            $requester = $_POST['requester'];
            $duration = $row['duration'];
            $approver = $_POST['approver'];
            $request_date = $row['request_date'];
            $end_date = $row['end_date'];

            $stmt->bind_param("ssssssssssssssssss", $request_no, $fullname, $branch, $department, $position, $application, $function, $role, $m_branch, $m_department, $m_position, $m_function, $m_role, $requester, $duration, $approver, $request_date, $end_date);
            if (!$stmt->execute()) {
                echo "Error inserting data: " . $stmt->error;
                break;
            }
        }
        $stmt->close();
    } else {
        echo "Upload pdf file only...";
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
        <main>
            <div class="container2">
                <a href="../assessment/assessment.php" class="back-button">
                    <i class="fa fa-chevron-circle-left" style="font-size:28px">Back</i>
                </a>
                <h2>Upload Move user </h2>

                <form action="upload_move.php" method="post" enctype="multipart/form-data">
                    <label for="file">Select file to Upload:</label>
                    <input class="upload" type="file" name="file" id="file">
                    <div class="input_content">
                        <input type="text" name="requester" required placeholder="Enter Requester">
                        <input type="text" name="approver" required placeholder="Enter Approver">
                    </div>
                    <div></div>
                    <input class="submit" type="submit" name="submit" value="Upload File" id="uploadButton">
                </form>

                <?php if ($alertMessage !== ""): ?>
                    <div class="alert alert-<?php echo $alertType; ?>" role="alert">
                        <?php echo $alertMessage; ?>
                    </div>
                <?php endif; ?>
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