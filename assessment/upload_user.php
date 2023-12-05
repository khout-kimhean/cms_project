<?php
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "demo"; // Change to your database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to sanitize input data
    function sanitizeData($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Process uploaded file
    if (isset($_FILES["fileInput"])) {
        $file = $_FILES["fileInput"];
        $fileTmpName = $file["tmp_name"];

        try {
            // Load the Excel file
            $spreadsheet = IOFactory::load($fileTmpName);
            $worksheet = $spreadsheet->getActiveSheet();

            // Define column mappings
            $columnMappings = array(
                'C4' => 'fullname',
                'C5' => 'branch',
                'F7' => 'position',
                'F10' => 'function',
                'D10' => 'role',
                'C21' => 'command',
                'C6' => 'duration',
                'D7' => 'department',
                'B22' => 'request_by',
                'B23' => 'request_date',
                'B24' => 'start_date',
                'B25' => 'end_date',
            );

            // Prepare SQL statement for data insertion
            $sql = "INSERT INTO tb_user (fullname, branch, position, function, role, command, duration, department, request_by, request_date, start_date, end_date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                'ssssssssssss',
                $fullname,
                $branch,
                $position,
                $function,
                $role,
                $command,
                $duration,
                $department,
                $request_by,
                $request_date,
                $start_date,
                $end_date
            );

            // Iterate through rows
            foreach ($worksheet->getRowIterator() as $row) {
                $rowData = array();
                $cellIterator = $row->getCellIterator();

                // Iterate through cells
                foreach ($cellIterator as $cell) {
                    $column = PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($cell->getColumn());
                    $cellValue = sanitizeData($cell->getFormattedValue());
                    $rowData[$column] = $cellValue;
                }

                // Map cell values to variables
                extract($rowData);

                // Debugging statements
                echo "Inserting data:\n";
                echo "Full Name: $fullname, Branch: $branch, Position: $position, ...\n";

                // Check if 'fullname' is not empty before insertion
                if (!empty($fullname)) {
                    // Execute the prepared statement
                    if (!$stmt->execute()) {
                        throw new Exception("Error in executing the statement: " . $stmt->error);
                    }
                    echo "Data inserted successfully\n";
                } else {
                    echo "Skipping row with empty 'fullname'\n";
                }
            }

        } catch (Exception $e) {
            echo 'Error loading file: ', $e->getMessage(), "\n";
        }

        // Close the prepared statement
        $stmt->close();
    }

    // Close database connection
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/assessment/upload_user.css">
    <title>Assessment</title>
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

                <a href="../data_store/search.php">
                    <span class="fa fa-search">
                    </span>
                    <h3>Search</h3>
                </a>
                <a href="../contact/contact.php">
                    <span class="fa fa-address-card">
                    </span>
                    <h3>Contact</h3>
                </a>
                <a href="../data_store/upload_file.php">
                    <span class="fa fa-upload">
                    </span>
                    <h3>Data Store</h3>
                </a>

                <a href="../data_store/list_upload.php">
                    <span class="material-icons-sharp">
                        inventory
                    </span>
                    <h3>View File</h3>
                </a>
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
                <a href="../data_store/data_mgt.php">
                    <span class="fa fa-briefcase">
                    </span>
                    <h3>Stock Mgt</h3>
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
                <h1 class="h1">Upload User Assessment</h1>
                <form method="post" enctype="multipart/form-data">
                    <div class="item">
                        <input class="file" type="file" name="fileInput" id="fileInput" />
                        <button type="submit">Submit</button>
                    </div>
                </form>
                <div class="table">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Branch</th>
                                <th>Position</th>
                                <th>Function</th>
                                <th>Role</th>
                                <th>Command</th>
                                <th>Duration Move</th>
                                <th>Department</th>
                                <th>Request By</th>
                                <th>Request Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Check if $rows is defined before looping
                            if (isset($rows) && is_array($rows)) {
                                // Loop through the fetched rows and display data in the table
                                foreach ($rows as $row) {
                                    echo "<tr>";
                                    echo "<td>{$row['fullname']}</td>";
                                    echo "<td>{$row['branch']}</td>";
                                    echo "<td>{$row['position']}</td>";
                                    echo "<td>{$row['function']}</td>";
                                    echo "<td>{$row['role']}</td>";
                                    echo "<td>{$row['command']}</td>";
                                    echo "<td>{$row['duration']}</td>";
                                    echo "<td>{$row['department']}</td>";
                                    echo "<td>{$row['request_by']}</td>";
                                    echo "<td>{$row['request_date']}</td>";
                                    echo "<td>{$row['start_date']}</td>";
                                    echo "<td>{$row['end_date']}</td>";
                                    echo "</tr>";
                                }
                            } else {
                                // Handle the case where $rows is not defined or is not an array
                                echo "<tr><td colspan='12'>No data available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
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
    <!-- <script src="orders.js"></script> -->
    <script src="../script/index.js"></script>
</body>

</html>