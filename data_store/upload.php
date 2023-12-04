<?php
// Include the PhpSpreadsheet library
require '../vendor/autoload.php';

// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'chatbotv2';

// Create a database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $originalFileName = $_FILES['file']['name'];

    if (!empty($originalFileName)) {
        // Check if the uploaded file is an Excel file (e.g., .xlsx)
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        if (in_array($fileExtension, ['xlsx', 'xls'])) {
            // Load the Excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);

            // Get the first worksheet
            $worksheet = $spreadsheet->getActiveSheet();

            // Get all the rows as an array
            $data = $worksheet->toArray();

            // Insert data into the database
            $sql = "INSERT INTO file_upload (title, description, filename) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sss", $title, $description, $filename);

                $successMessage = "Rows inserted successfully.";

                foreach ($data as $row) {
                    // Assuming the Excel file has three columns (title, description, filename)
                    $title = $row[0];
                    $description = $row[1];
                    $filename = $originalFileName; // Use the original filename

                    if (!$stmt->execute()) {
                        $successMessage = "Error inserting data: " . $stmt->error;
                        break; // Exit the loop on the first error
                    }
                }

                $stmt->close();
            } else {
                $successMessage = "Error preparing SQL statement: " . $conn->error;
            }

            // Close the database connection
            $conn->close();

            // Create a structured JSON response
            $response = array(
                'status' => ($successMessage === "Rows inserted successfully") ? 'success' : 'error',
                'message' => $successMessage
            );

            // Send the JSON response to the client
            header('Content-Type: application/json');
            echo json_encode($response);

            // Exit the script to prevent any unintended output
            exit();
        } else {
            echo "Error: Please upload a valid Excel file (xlsx or xls).";
        }
    } else {
        echo "Error: File not uploaded or empty.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="../Admin Dashboard/admin.css"> -->
    <link rel="stylesheet" type="text/css" href="../Admin Dashboard/styles/style_upload.css">

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <title>Admin Dashboard</title>
</head>

<body>

    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo">
                    <img src="../Admin Dashboard/images/profile.jpg">
                    <h2>FTB <span class="danger">Bank</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">
                        close
                    </span>
                </div>
            </div>

            <div class="sidebar">
                <a href="../Admin Dashboard/admin.php" class="active">
                    <span class="material-icons-sharp">
                        dashboard
                    </span>
                    <h3>Dashboard</h3>
                </a>
                <!-- <a href="../templates/createuser.php">
                    <span class="fa fa-user-circle-o">
                    </span>
                    <h3>Create User</h3>
                </a> -->
                <a href="../templates/search.php">
                    <span class="fa fa-search">
                    </span>
                    <h3>Search</h3>
                </a>
                <!-- <a href="#">
                    <span class="material-icons-sharp">
                        insights
                    </span>
                    <h3>Analytics</h3>
                </a> -->
                <a href="../templates/email.php">
                    <span class="fa fa-address-card">
                    </span>
                    <h3>Contact</h3>
                </a>
                <a href="../templates/multi_upload.php">
                    <span class="fa fa-upload">
                    </span>
                    <h3>Multi_Upload</h3>
                </a>
                <a href="../templates/list_upload.php">
                    <span class="material-icons-sharp">
                        inventory
                    </span>
                    <h3>Data List</h3>
                </a>
                <!-- <a href="#">
                    <span class="material-icons-sharp">
                        report_gmailerrorred
                    </span>
                    <h3>Reports</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp">
                        settings
                    </span>
                    <h3>Settings</h3>
                </a> -->
                <a href="../templates/login.php">
                    <span class="material-icons-sharp">
                        add
                    </span>
                    <h3>New Login</h3>
                </a>
                <a href="../templates/logout.php">
                    <span class="material-icons-sharp">
                        logout
                    </span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main class="container2">
            <div>
                <div>
                    <div class="header">
                        <a href="../Admin Dashboard/admin.php" class="back-button"><i class="fa fa-chevron-circle-left"
                                style="font-size:36px"></i></a>
                        <h2>Upload a File</h2>
                    </div>

                </div>

                <div>
                    <form method="post" enctype="multipart/form-data" id="uploadForm" onsubmit="submitForm(event)">

                        <label for="file">Select file to Upload:</label>
                        <input type="file" name="file" id="file">

                        <input type="submit" name="submit" value="Upload File" id="uploadButton">
                        <div id="uploadMessage" class="message"></div>
                        <!-- <p><a href="../Admin Dashboard/admin.php">Go Back</a></p> -->
                    </form>
                </div>
            </div>
            <script>
                function submitForm(event) {
                    event.preventDefault(); // Prevent the default form submission

                    const form = document.getElementById("uploadForm");
                    const fileInput = document.getElementById("file");
                    const uploadButton = document.getElementById("uploadButton");
                    const uploadMessage = document.getElementById("uploadMessage");

                    uploadMessage.innerHTML = ""; // Clear any previous message
                    uploadButton.disabled = true; // Disable the submit button

                    const formData = new FormData(form);

                    fetch("upload.php", {
                        method: "POST",
                        body: formData,
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.status === "success") {
                                uploadMessage.innerHTML = '<div class="success-message">' + data.message + '</div>';
                            } else {
                                uploadMessage.innerHTML = '<div class="error-message">' + data.message + '</div>';
                            }
                        })
                        .catch((error) => {
                            uploadMessage.innerHTML = '<div class="error-message">Error: ' + error + '</div>';
                        })
                        .finally(() => {
                            uploadButton.disabled = false; // Re-enable the submit button after processing
                        });
                }

            </script>
        </main>

        <!-- End of Main Content -->

        <!-- Right Section -->
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
                        <img src="../Admin Dashboard/images/profile.jpg ">
                    </div>
                </div>

            </div>
            <!-- End of Nav -->

            <div class="user-profile">
                <div class="logo">
                    <img src="../Admin Dashboard/images/profile.jpg">
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
    <script src="../Admin Dashboard/index.js"></script>
    <!-- <script src="../Admin Dashboard/submit.js"></script> -->

</body>

</html>