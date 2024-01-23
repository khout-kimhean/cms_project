<?php
include '../dashboard/check_access.php';
require '../vendor/autoload.php';
include '../connect/role_access.php';

$host = "localhost";
$user = "root";
$pass = "";
$db = "demo";

$con = mysqli_connect($host, $user, $pass, $db);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "demo";

    $con = new mysqli($servername, $username, $password, $database);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
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
$sql = "SELECT * FROM user_new";
$result1 = mysqli_query($con, $sql);


$searchResults = array();
$res = array();

if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];

    $sql = "SELECT * FROM user_new WHERE display_name LIKE ? OR request_no LIKE ? OR branch LIKE ? ";
    $stmt = mysqli_prepare($con, $sql);
    $searchPattern = "%" . $searchTerm . "%";
    mysqli_stmt_bind_param($stmt, "sss", $searchPattern, $searchPattern, $searchPattern);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }
    }
}

if (isset($_POST['from_date']) && isset($_POST['to_date']) && isset($_POST['filter'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $query = "SELECT * FROM user_new WHERE upload_date BETWEEN ? AND ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $from_date, $to_date);

        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            // Get the result
            $res = mysqli_stmt_get_result($stmt);
        } else {
            echo "Error executing the statement: " . mysqli_error($con);
        }
        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing the statement: " . mysqli_error($con);
    }
}
if (isset($_POST['export'])) {
    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Request No');
    $sheet->setCellValue('C1', 'Full Name');
    $sheet->setCellValue('D1', 'Branch');
    $sheet->setCellValue('E1', 'Department');
    $sheet->setCellValue('F1', 'Position');
    $sheet->setCellValue('G1', 'Function');
    $sheet->setCellValue('H1', 'Role');
    $sheet->setCellValue('I1', 'Application');
    $sheet->setCellValue('J1', 'Request By');
    $sheet->setCellValue('K1', 'Request Date');
    $sheet->setCellValue('L1', 'Approve By');
    $sheet->setCellValue('M1', 'Create Date');
    $sheet->setCellValue('N1', 'Comment');

    if (isset($result1) && mysqli_num_rows($result1) > 0 || !empty($res)) {
        $id = 1;
        $rowNum = 2;

        // Loop through $result1 if it's defined
        if (isset($result1) && mysqli_num_rows($result1) > 0) {
            while ($row = mysqli_fetch_assoc($result1)) {
                // Export row to Excel
                exportToExcel($sheet, $rowNum, $id++, $row);
                $rowNum++;
            }
        }

        // Loop through $res if it's defined
        foreach ($res as $row) {
            // Export row to Excel
            exportToExcel($sheet, $rowNum, $id++, $row);
            $rowNum++;
        }
    } else {
        $sheet->setCellValue('A2', 'No matching users found');
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=summary_export.xlsx');
    header('Cache-Control: max-age=0');

    // Create the Excel writer
    $writer = new Xlsx($spreadsheet);

    try {
        ob_end_clean();
        $writer->save('php://output');
        exit();
    } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
        echo 'Error exporting spreadsheet: ', $e->getMessage();
        exit();
    }
}

$con->close();
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
        const dataType = 'application/vnd.ms-excel';
        const table = document.getElementById(tableId);
        const tableHTML = table.outerHTML.replace(/ /g, '%20');

        // Create a Blob from the HTML table
        const blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });

        // Create a download link
        const downloadLink = document.createElement('a');
        downloadLink.href = URL.createObjectURL(blob);

        // Setting the file name
        downloadLink.download = filename;

        // Append the link to the body
        document.body.appendChild(downloadLink);

        // Trigger the download
        downloadLink.click();

        // Remove the link from the body
        document.body.removeChild(downloadLink);
    }
    </script>
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

        <main>

            <h2>List User Assessment</h2>
            <div class="container2">
                <div class="find-user">
                    <form action="list_new_user.php" method="post" name="form-search">
                        <div class="find-form-group">
                            <label for="searchTerm">Search by ID/ Name/ Branch :</label>
                            <input type="text" name="searchTerm" class="form-control" id="searchTerm">
                        </div>

                        <input type="submit" name="search" value="Search" class="btn btn-info">
                    </form>
                </div>
                <form method="post" action="">
                    <div class="form-container">
                        <label># From date</label>
                        <input type="date" name="from_date" value="<?php if (isset($_GET['from_date'])) {
                            echo $_GET['from_date'];
                        } ?>" class="form-control">
                    </div>
                    <div class="form-container">
                        <label># To Date</label>
                        <input type="date" name="to_date" value="<?php if (isset($_GET['to_date'])) {
                            echo $_GET['to_date'];
                        } ?>" class="form-control">
                    </div>
                    <div class="form-container">
                        <button type="submit" name="filter" class="btn-primary1">Filter</button>

                    </div>
                </form>
                <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js">
                </script>
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
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Request No</th>
                                    <th>Full Name</th>
                                    <th>Branch</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Function</th>
                                    <th>Role</th>
                                    <th>Application Name</th>
                                    <th>requester</th>
                                    <th>Approver</th>
                                    <th>Request_date</th>
                                    <th>comment</th>
                                    <th>Create Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                                <?php
                                if (!empty($searchResults)) {
                                    $id = 1;
                                    foreach ($searchResults as $row) {
                                        printTableRow($id++, $row);
                                    }

                                } elseif (isset($_POST['from_date']) && isset($_POST['to_date']) && isset($_POST['filter'])) {
                                    // Display search results only if search is performed
                                    if ($res && mysqli_num_rows($res) > 0) {
                                        $id = 1;
                                        while ($row = mysqli_fetch_array($res)) {
                                            printTableRow($id++, $row);
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No matching users found.</td></tr>";
                                    }
                                } elseif ($result1->num_rows > 0) {
                                    $id = 1;
                                    while ($row = $result1->fetch_assoc()) {
                                        printTableRow($id++, $row);
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No matching users found.</td></tr>";
                                }

                                function printTableRow($id, $row)
                                {
                                    ?>
                                <tr>
                                    <td>
                                        <?php echo $id++; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['request_no']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['display_name']); ?>
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
                                        <?php echo htmlspecialchars($row['application']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['requester']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['approver']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['request_date']); ?>
                                    </td>
                                    <td <?php echo htmlspecialchars($row['comment']); ?>>
                                        <?php
                                            $comment = htmlspecialchars($row['comment']);
                                            echo strlen($comment) > 20 ? substr($comment, 0, 20) . '...' : $comment;
                                            ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['upload_date']); ?>
                                    </td>
                                    <td>
                                        <a href="list_new_user.php?id=<?php echo $row['id']; ?>">Delete</a>
                                        ||
                                        <a class="click1" href="edit_user_new.php?id=<?php echo $row['id']; ?>">Edit</a>
                                    </td>
                                </tr>
                                <?php
                                }
                                function exportToExcel($sheet, $rowNum, $id, $row)
                                {
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
                                    $sheet->setCellValue('M' . $rowNum, $row['upload_date']);
                                    $sheet->setCellValue('N' . $rowNum, $row['comment']);

                                    // printTableRow($id++, $row);
                                    $rowNum++;
                                    $id++;
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