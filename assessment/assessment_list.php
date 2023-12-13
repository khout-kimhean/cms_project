<?php
require '../vendor/autoload.php';

$host = "localhost";
$user = "root";
$pass = "";
$db = "demo";


// Create a connection to the database
$con = mysqli_connect($host, $user, $pass, $db);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "demo";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }



    $sqlMergesumary_data = "INSERT IGNORE INTO data_export (display_name, position, function, role, branch, status, requester, approver, start_date, end_date, command)
    SELECT display_name, position, function, role, branch, status, requester, approver, start_date, end_date, command
    FROM sumary_data";

    if ($conn->query($sqlMergesumary_data) === TRUE) {
        $message = "Data from create_user merged successfully.";
    } else {
        $message = "Error merging data from sumary_data: " . $conn->error;
    }

    $sqlMergemove_user = "INSERT IGNORE INTO  data_export (display_name, position, function, role, branch, status, requester, approver, start_date, end_date, command)
    SELECT display_name, position, function, role,branch, status, requester, approver, start_date, end_date, command
    FROM move_user";

    if ($conn->query($sqlMergemove_user) === TRUE) {
        $message .= "<br>Data from move_user merged successfully.";
    } else {
        $message .= "<br>Error merging data from move_user: " . $conn->error;
    }


    $sqlMergeresign_user = "INSERT IGNORE INTO data_export (display_name, position, function, role, branch, status, approver,  end_date, command)
    SELECT display_name, position, function, role,branch, status, approver, end_date, command
    FROM resign_user";

    if ($conn->query($sqlMergeresign_user) === TRUE) {
        $message .= "<br>Data from resign_user merged successfully.";
    } else {
        $message .= "<br>Error merging data from resign_user: " . $conn->error;
    }

    // echo $message; 

    $conn->close();
} else {
    $message = ""; // No data insertion on page load
}
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$alertMessage = ''; // Initialize alert message
$alertType = ''; // Initialize alert type



if (isset($_POST['submit'])) {
    // Handle the "Move" action here
    // Your existing code for the "Move" action
} elseif (isset($_POST['delete'])) {
    // Handle the "Delete All Data" action
    $deleteQuery = "DELETE FROM data_export";
    if (mysqli_query($con, $deleteQuery)) {
        $alertType = 'success';
        $alertMessage = 'All data deleted successfully!';
    } else {
        $alertType = 'danger';
        $alertMessage = 'Error: ' . mysqli_error($con);
    }
}
// Delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    $deleteSql = "DELETE FROM data_export WHERE id = ?";
    $stmt = $con->prepare($deleteSql);
    $stmt->bind_param("i", $idToDelete);

    if ($stmt->execute()) {
        header("Location: assessment_list.php?st=delete-success");
        exit;
    } else {
        header("Location: assessment_list.php?st=delete-error");
        exit;
    }
}


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['export'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $sheet->setCellValue('A1', 'DisplayName');
    $sheet->setCellValue('B1', 'Branch');
    $sheet->setCellValue('C1', 'Position');
    $sheet->setCellValue('D1', 'Function');
    $sheet->setCellValue('E1', 'Role');
    $sheet->setCellValue('F1', 'Command');

    // Fetch data from the database and populate the Excel sheet
    $sql = "SELECT * FROM sumary_data ORDER BY id ASC";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $rowNum = 2;
        while ($row = mysqli_fetch_assoc($result)) {
            $sheet->setCellValue('A' . $rowNum, $row['display_name']);
            $sheet->setCellValue('B' . $rowNum, $row['branch']);
            $sheet->setCellValue('C' . $rowNum, $row['position']);
            $sheet->setCellValue('D' . $rowNum, $row['function']);
            $sheet->setCellValue('E' . $rowNum, $row['role']);
            $sheet->setCellValue('F' . $rowNum, $row['command']);
            $rowNum++;
        }
    }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="data.xlsx"');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    exit();
}

mysqli_close($con);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/assessment/assessment_list.css">
    <title>Admin Dashboard</title>
    <script>
    function exportTableToExcel(tableId, filename = '') {
        let downloadLink;
        const dataType = 'application/vnd.ms-excel';
        const table = document.getElementById(tableId);
        const tableHTML = table.outerHTML.replace(/ /g, '%20');

        // Create a download link element
        downloadLink = document.createElement('a');

        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            const blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }
    </script>

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
                <a href="../contact/contact.php">
                    <span class="fa fa-address-card">
                    </span>
                    <h3>Contact</h3>
                </a>
                <a href="../data_store/data_mgt.php">
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
                <a href="../assessment/assessment.php" class="active">
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
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>DisplayName</th>
                                    <th>Branch</th>
                                    <th>Position</th>
                                    <th>Function</th>
                                    <th>Role</th>
                                    <th>Command</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $host = "localhost";
                                $user = "root";
                                $pass = "";
                                $db = "demo";

                                $con = mysqli_connect($host, $user, $pass, $db);

                                if (!$con) {
                                    die("Connection failed: " . mysqli_connect_error());
                                }
                                $sql = "SELECT * FROM data_export ORDER BY id ASC";
                                $result = mysqli_query($con, $sql);

                                if ($result && mysqli_num_rows($result) > 0) {
                                    $i = 1;
                                    while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>

                                    <td>
                                        <?php echo htmlspecialchars($row['display_name']); ?>
                                    </td>
                                    <td title="<?php echo htmlspecialchars($row['branch']); ?>">
                                        <?php
                                                $branch = htmlspecialchars($row['branch']);
                                                echo strlen($branch) > 20 ? substr($branch, 0, 16) . '...' : $branch;
                                                ?>
                                    </td>
                                    <td title="<?php echo htmlspecialchars($row['position']); ?>">
                                        <?php
                                                $position = htmlspecialchars($row['position']);
                                                echo strlen($position) > 20 ? substr($position, 0, 16) . '...' : $position;
                                                ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['function']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['role']); ?>
                                    </td>
                                    <td title="<?php echo htmlspecialchars($row['command']); ?>">
                                        <?php
                                                $command = htmlspecialchars($row['command']);
                                                echo strlen($command) > 20 ? substr($command, 0, 10) . '...' : $command;
                                                ?>
                                    </td>
                                    <td>
                                        <a href="../templates/assessment_list.php?delete=<?php echo $row['id']; ?>">
                                            Delete</a>

                                    </td>

                                </tr>
                                <?php }
                                } else {
                                    echo "<tr><td colspan='7'>No files found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="buttonx">
                <form method="post" action="assessment_list.php">
                    <button id="exportButton" type="submit" name="export">Export to Excel</button>
                </form>

                <!-- <button id="exportButton" onclick="exportTableToExcel('exportTable', 'data')">Export to Excel</button>   -->
                <form method="post" action="assessment_list.php">
                    <button id="exportButton" type="submit" name="delete" class="form-btn-delete">Delete All
                        Data</button>
                </form>
                <form action="assessment_list.php" method="post">
                    <input id="exportButton" type="submit" value="Insert Data">
                </form>
                <form action="../assessment/assessment.php" method="post">
                    <input id="exportButton" type="submit" value="<< Back">
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
    <script src="../script/index.js"></script>
</body>

</html>