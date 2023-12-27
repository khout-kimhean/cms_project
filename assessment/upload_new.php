<?php

require '../vendor/autoload.php';

include '../connect/conectdb.php';
include '../connect/role_access.php';
$alertType = "";
$alertMessage = "";
// Create a database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!empty($fileName) && in_array($fileExtension, ['xlsx', 'xls'])) {
        // Load the Excel file
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
        $worksheet = $spreadsheet->getActiveSheet();

        // Prepare the SQL statement outside the loop
        $sql = "INSERT INTO user_move (request_no, fullname, branch, department, position, application, function, role, m_branch, m_department, m_position, m_function, m_role,  requester,duration, comment , request_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "Error preparing SQL statement: " . $conn->error;
            exit;
        }

        // Bind parameters outside the loop
        $stmt->bind_param("sssssssssssssssss", $request_no, $fullname, $branch, $department, $position, $application, $function, $role, $m_branch, $m_department, $m_position, $m_function, $m_role, $requester, $duration, $comment, $request_date);

        // Iterate through rows starting from the 7th row (adjust based on your data)
        for ($rowIndex = 7; $rowIndex <= 10; $rowIndex++) {
            // Extracting values based on the structure of your data
            $request_no_cell = $worksheet->getCell('B' . ($rowIndex - 4));
            $request_no_value = $request_no_cell->getValue();
            $matches = [];
            preg_match('/Request No: (\d+)/', $request_no_value, $matches);
            $request_no = isset($matches[1]) ? $matches[1] : '';


            $fullname = $worksheet->getCell('C' . ($rowIndex + 12))->getValue();

            // Remove unnecessary text from the fullname
            $fullname = str_replace('Name: ', '', $fullname);
            if ($rowIndex % 7 !== 0) {
                continue;
            }

            $branch = $worksheet->getCell('C' . $rowIndex)->getValue();

            $branch = str_replace('Branch: ', '', $branch);
            if ($rowIndex % 7 !== 0) {
                continue;
            }

            // m_branch
            $m_branch = $worksheet->getCell('C' . $rowIndex)->getValue();

            $m_branch = str_replace('Branch: ', '', $m_branch);
            if ($rowIndex % 7 !== 0) {
                continue;
            }



            $department = $worksheet->getCell('D' . $rowIndex)->getValue();

            $department = str_replace('Department: ', '', $department);
            if ($rowIndex % 7 !== 0) {
                continue;
            }


            // m_department
            $m_department = $worksheet->getCell('D' . ($rowIndex + 6))->getValue();

            $m_department = str_replace('Department: ', '', $m_department);
            if ($rowIndex % 7 !== 0) {
                continue;
            }



            $position = $worksheet->getCell('F' . $rowIndex)->getValue();

            $position = str_replace('Position: ', '', $position);
            if ($rowIndex % 7 !== 0) {
                continue;
            }
            // m_position
            $m_position = $worksheet->getCell('F' . ($rowIndex + 6))->getValue();

            $m_position = str_replace('Position: ', '', $m_position);
            if ($rowIndex % 7 !== 0) {
                continue;
            }


            $application = $worksheet->getCell('C' . ($rowIndex + 4))->getValue();
            $function = $worksheet->getCell('F' . ($rowIndex + 4))->getValue();
            $function = str_replace(': ', '', $function);
            if ($rowIndex % 7 !== 0) {
                continue;
            }
            // m_function
            $m_function = $worksheet->getCell('F' . ($rowIndex + 10))->getValue();
            $m_function = str_replace(': ', '', $m_function);
            if ($rowIndex % 7 !== 0) {
                continue;
            }
            $m_role = $worksheet->getCell('D' . ($rowIndex + 10))->getValue();

            $role = $worksheet->getCell('D' . ($rowIndex + 4))->getValue();

            $requester = $worksheet->getCell('B' . ($rowIndex + 15))->getValue();

            $requester = preg_replace('/(?:Name:|Date: \d{1,2}\/[a-zA-Z]+\/\d{4})/', '', $requester);
            if ($rowIndex % 7 !== 0) {
                continue;
            }

            $duration = $worksheet->getCell('C' . ($rowIndex - 1))->getValue();

            $duration = str_replace(': ', '', $duration);
            if ($rowIndex % 7 !== 0) {
                continue;
            }

            $comment = $worksheet->getCell('C' . ($rowIndex + 14))->getValue();

            $comment = str_replace('Description: ', '', $comment);
            if ($rowIndex % 7 !== 0) {
                continue;
            }
            $request_date_cell = $worksheet->getCell('B' . ($rowIndex + 15));
            $request_date_value = $request_date_cell->getValue();
            $matches = [];
            preg_match('/Date: (\S+)/', $request_date_value, $matches);
            $request_date = isset($matches[1]) ? $matches[1] : '';
            $alertType = "success";
            $alertMessage = "File uploaded successfully.";
            if (!$stmt->execute()) {
                $alertType = "danger";
                $alertMessage = "Error inserting data: " . $stmt->error;
                $stmt->close();
                $conn->close();
                break;  // Exit the loop if there's an error
            }

            $alertType = "success";
            $alertMessage = "File uploaded successfully.";
        }

        $stmt->close();
        $conn->close();

    } else {
        $alertType = "danger";
        $alertMessage = "Invalid file format. Please upload an Excel file.";
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
                    <label for="file">Select Excel File to Upload:</label>
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