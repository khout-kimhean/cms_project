<?php
include '../dashboard/check_access.php';
require '../vendor/autoload.php';

$host = "localhost";
$user = "root";
$pass = "";
$db = "demo";

$con = mysqli_connect($host, $user, $pass, $db);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "demo";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sqlMergesumary_data = "
    INSERT IGNORE INTO assessment_user (
        request_no, request_for, branch, department, position, 
        `function`, role, m_branch, m_department, m_position, 
        m_function, m_role, application, duration, requester, 
        request_date, approver, process_by, technical_process, 
        status, comment
    )
    SELECT 
        request_no, 
        fullname AS request_for, 
        branch, 
        department, 
        position, 
        `function`, 
        role, 
        m_branch, 
        m_department, 
        m_position, 
        m_function, 
        m_role, 
        application, 
        duration, 
        requester, 
        request_date, 
        '' AS approver, 
        '' AS process_by, 
        '' AS technical_process, 
        '' AS status, 
        comment
    FROM 
        user_move";

    // Use prepared statements to prevent SQL injection

    if (isset($_POST['export'])) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID');
        $sheet->setCellValue('C1', 'Request For');
        $sheet->setCellValue('D1', 'Branch');
        $sheet->setCellValue('E1', 'Department');
        $sheet->setCellValue('F1', 'Position');
        $sheet->setCellValue('G1', 'Function');
        $sheet->setCellValue('H1', 'Role');
        $sheet->setCellValue('I1', 'Move to Branch');
        $sheet->setCellValue('J1', 'Move to Department');
        $sheet->setCellValue('K1', 'Move to Position');
        $sheet->setCellValue('L1', 'Move to Function');
        $sheet->setCellValue('M1', 'Move to Role');
        $sheet->setCellValue('N1', 'Duration');
        $sheet->setCellValue('O1', 'Request By');
        $sheet->setCellValue('P1', 'Request Date');
        $sheet->setCellValue('Q1', 'Approve By');
        $sheet->setCellValue('R1', 'Process By');
        $sheet->setCellValue('S1', 'Technical Process');
        $sheet->setCellValue('T1', 'Status');
        $sheet->setCellValue('U1', 'Not');

        $sql = "SELECT * FROM assessment_user ORDER BY id ASC";
        $result = mysqli_query($con, $sql);
        $id = 1;
        if ($result && mysqli_num_rows($result) > 0) {
            $rowNum = 2;

            while ($row = mysqli_fetch_assoc($result)) {
                $sheet->setCellValue('A' . $rowNum, $id);
                $sheet->setCellValue('B' . $rowNum, $row['request_no']);
                $sheet->setCellValue('C' . $rowNum, $row['request_for']);
                $sheet->setCellValue('D' . $rowNum, $row['branch']);
                $sheet->setCellValue('E' . $rowNum, $row['department']);
                $sheet->setCellValue('F' . $rowNum, $row['position']);
                $sheet->setCellValue('G' . $rowNum, $row['function']);
                $sheet->setCellValue('H' . $rowNum, $row['role']);
                $sheet->setCellValue('I' . $rowNum, $row['m_branch']);
                $sheet->setCellValue('J' . $rowNum, $row['m_department']);
                $sheet->setCellValue('K' . $rowNum, $row['m_position']);
                $sheet->setCellValue('L' . $rowNum, $row['m_function']);
                $sheet->setCellValue('M' . $rowNum, $row['m_role']);
                $sheet->setCellValue('N' . $rowNum, $row['duration']);
                $sheet->setCellValue('O' . $rowNum, $row['requester']);
                $sheet->setCellValue('P' . $rowNum, $row['request_date']);
                $sheet->setCellValue('Q' . $rowNum, $row['approver']);
                $sheet->setCellValue('R' . $rowNum, $row['process_by']);
                $sheet->setCellValue('S' . $rowNum, $row['technical_process']);
                $sheet->setCellValue('T' . $rowNum, $row['status']);
                $sheet->setCellValue('U' . $rowNum, $row['comment']);
                $rowNum++;
                $id++;
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="assessment_user.xlsx"');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        exit();
    } elseif (isset($_POST['delete'])) {
        $deleteQuery = "DELETE FROM assessment_user";
        if (mysqli_query($con, $deleteQuery)) {
            $alertType = 'success';
            $alertMessage = 'All data deleted successfully!';
            header("Location: assessment_user.php"); // Redirect after successful delete
            exit();
        } else {
            $alertType = 'danger';
            $alertMessage = 'Error: Data deletion failed. Please contact the administrator.';
            // Log the error to a secure location
            error_log("Error executing delete query: " . $deleteQuery . "\n" . mysqli_error($con));
        }
    }

    if ($conn->query($sqlMergesumary_data) === TRUE) {
        // Code for other operations after data insertion
    } else {
        $alertType = 'danger';
        $alertMessage = 'Error: Data insertion failed. Please contact the administrator.';
        // Log the error to a secure location
        error_log("Error executing insert query: " . $sqlMergesumary_data . "\n" . $conn->error);
    }

    $conn->close();
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
    <link rel="stylesheet" type="text/css" href="../styles/assessment/assessment_user.css">
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
                <!-- <a href="../contact/contact.php">
                    <span class="fa fa-address-card">
                    </span>
                    <h3>Contact</h3>
                </a> -->
                <a href="../file/file_mgt.php">
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
            <h2>List User Assessment</h2>
            <div class="container2">
                <div class="buttonx">
                    <form method="post" action="assessment_user.php">
                        <button id="exportButton" type="submit" name="export">Export to Excel</button>
                    </form>

                    <!-- <button id="exportButton" onclick="exportTableToExcel('exportTable', 'data')">Export to Excel</button>   -->
                    <form method="post" action="assessment_user.php">
                        <button id="exportButton" type="submit" name="delete" class="form-btn-delete">Delete All
                            Data</button>
                    </form>
                    <form action="assessment_user.php" method="post">
                        <input id="exportButton" type="submit" value="Insert Data">
                    </form>
                    <form action="../assessment/assessment.php" method="post">
                        <input id="exportButton" type="submit" value="<< Back">
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Request For</th>
                                    <th>Branch</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Function</th>
                                    <th>Role</th>
                                    <th>Move to Branch</th>
                                    <th>Move to Department</th>
                                    <th>Move to Position</th>
                                    <th>Move to Function</th>
                                    <th>Move to Role</th>
                                    <th>Duration</th>
                                    <th>Request By</th>
                                    <th>Request Date</th>
                                    <th>Approve By</th>
                                    <th>Process By</th>
                                    <th>Technical Process</th>
                                    <th>Status</th>
                                    <th>Note</th>
                                    <th>Action</th>
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
                                $sql = "SELECT * FROM assessment_user ORDER BY id ASC";
                                $result = mysqli_query($con, $sql);

                                if ($result && mysqli_num_rows($result) > 0) {
                                    $id = 0; // Initialize the counter
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo ++$id; ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['request_no']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['request_for']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['branch']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['department']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['position']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['function']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['role']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['m_branch']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['m_department']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['m_position']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['m_function']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['m_role']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['duration']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['requester']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['request_date']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['approver']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['process_by']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['technical_process']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($row['status']); ?>
                                            </td>
                                            <td <?php echo htmlspecialchars($row['comment']); ?>>
                                                <?php
                                                $comment = htmlspecialchars($row['comment']);
                                                echo strlen($comment) > 20 ? substr($comment, 0, 20) . '...' : $comment;
                                                ?>
                                            </td>
                                            <td>

                                                <a href=" ../templates/assessment_user.php?delete=<?php echo $row['id']; ?>">
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


    <script src="../script/index.js"></script>
</body>

</html>