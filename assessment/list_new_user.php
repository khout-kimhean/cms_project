<?php
include '../dashboard/check_access.php';
require '../vendor/autoload.php';
include '../connect/role_access.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$host = "localhost";
$user = "root";
$pass = "";
$db = "demo";

$con = mysqli_connect($host, $user, $pass, $db);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
    $sheet->setCellValue('I1', 'Application');
    $sheet->setCellValue('J1', 'Request By');
    $sheet->setCellValue('K1', 'Request Date');
    $sheet->setCellValue('L1', 'Approve By');
    $sheet->setCellValue('M1', 'Not');

    $sql = "SELECT * FROM user_new ORDER BY id ASC";
    $result = mysqli_query($con, $sql);
    $id = 1;

    if ($result && mysqli_num_rows($result) > 0) {
        $rowNum = 2;

        while ($row = mysqli_fetch_assoc($result)) {
            $sheet->setCellValue('A' . $rowNum, $id);
            $sheet->setCellValue('B' . $rowNum, $row['request_no']);
            $sheet->setCellValue('C' . $rowNum, $row['display_name']);
            $sheet->setCellValue('D' . $rowNum, $row['branch']);
            $sheet->setCellValue('E' . $rowNum, $row['department']);
            $sheet->setCellValue('F' . $rowNum, $row['position']);
            $sheet->setCellValue('G' . $rowNum, $row['function']);
            $sheet->setCellValue('H' . $rowNum, $row['role']);
            $sheet->setCellValue('I' . $rowNum, $row['application']);
            $sheet->setCellValue('J' . $rowNum, $row['requester']);
            $sheet->setCellValue('K' . $rowNum, $row['request_date']);
            $sheet->setCellValue('L' . $rowNum, $row['approver']);
            $sheet->setCellValue('M' . $rowNum, $row['comment']);  // Remove htmlspecialchars here
            $rowNum++;
            $id++;
        }
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="assessment_user.xlsx"');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);

    // Perform deletion query
    $deleteQuery = "DELETE FROM user_new WHERE id = '$id'";
    if (mysqli_query($con, $deleteQuery)) {
        // Redirect to the list page after successful deletion
        header("Location: list_new_user.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($con);
    }
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
                <a href="../file/file_mgt.php" <?php echo isLinkDisabled('file_mgt.php'); ?>>
                    <span class="fa fa-upload">
                    </span>
                    <h3>Store File</h3>
                </a>
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
                <a href="../user_mgt/logout.php">
                    <span class="material-icons-sharp">
                        logout
                    </span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>
        <main>
            <h2>List New User</h2>
            <div class="container2">
                <div class="buttonx">
                    <form method="post" action="list_new_user.php">
                        <button id="exportButton" type="submit" name="export">Export to Excel</button>
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
                                    <th>Application</th>
                                    <th>Branch</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Function</th>
                                    <th>Role</th>
                                    <th>Request By</th>
                                    <th>Request Date</th>
                                    <th>Approve By</th>
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
                                $sql = "SELECT * FROM user_new ORDER BY id ASC";
                                $result = mysqli_query($con, $sql);

                                if ($result && mysqli_num_rows($result) > 0) {
                                    $id = 0;
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
                                        <?php echo htmlspecialchars($row['display_name']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['application']); ?>
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
                                        <?php echo htmlspecialchars($row['requester']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['request_date']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['approver']); ?>
                                    </td>
                                    <td>
                                        <?php
                                                $comment = htmlspecialchars($row['comment']);
                                                echo strlen($comment) > 20 ? substr($comment, 0, 20) . '...' : $comment;
                                                ?>
                                    </td>
                                    <td>
                                        <a class="click1" href="edit_user_new.php?id=<?php echo $row['id']; ?>">Edit</a>
                                        ||
                                        <a href="list_new_user.php?id=<?php echo $row['id']; ?>">Delete</a>
                                    </td>
                                </tr>
                                <?php
                                    }
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
        <div class=" right-section">
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
    <script src="../script/index.js"></script>

</body>

</html>