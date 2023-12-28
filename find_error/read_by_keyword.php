<?php
// session_start();

// Include the file with the access check
include '../dashboard/check_access.php';
include '../connect/role_access.php';
// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'demo';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = array();

$sql = "SELECT * FROM login_register";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Admin Dashboard/styles/todo_management.css">
    <link rel="stylesheet" type="text/css" href="../styles/find_error/read_by_keyword.css">
    <title>Admin Dashboard</title>

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
                        <!-- close -->
                    </span>
                </div>
            </div>

            <div class="sidebar">
                <a href="../dashboard/dashboard.php" class="active">
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
                <a href="../assessment/assessment.php" <?php echo isLinkDisabled('assessment.php'); ?>>
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
                <div class="head">
                    <a href="../find_error/read_file.php" class="back-button">
                        <i class="fa fa-chevron-circle-left" style="font-size:28px ">Back</i>
                    </a>
                    <h2></h2>
                </div>

                <!-- <label for="searchKeyNumberInput">Enter Reference Number:</label> -->
                <div class="item">
                    <input class="file" type="file" id="fileInput" />
                    <input class="search" type="text" id="searchKeyNumberInput" />
                    <br />
                </div>
                <button onclick="processFile()">Show </button>
                <div id="output"></div>

                <script>
                function processFile() {
                    const searchKeyNumberInput = document.getElementById('searchKeyNumberInput');
                    const fileInput = document.getElementById('fileInput');
                    const outputDiv = document.getElementById('output');
                    outputDiv.innerHTML = ''; // Clear previous output

                    const file = fileInput.files[0];
                    const searchKeyNumber = searchKeyNumberInput.value.trim();

                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const fileContent = e.target.result;
                            const entries = fileContent.split('\n');

                            let found = false;
                            let result = '';

                            entries.forEach(entry => {
                                if (entry.includes(`RETR_REF_NO :${searchKeyNumber}`)) {
                                    found = true;
                                    result += entry + '\n';
                                }
                            });

                            // Display the result
                            if (result.trim() !== '') {
                                outputDiv.innerText = result +
                                    " \n*Please Re-Enter # Key-No # And click --> Show Detail  To Show Detail of TRX:\n\n ";
                                outputDiv.innerHTML += '<button onclick="searchKeyword()">Show Detail</button>';
                            } else {
                                outputDiv.innerText = "No log entry found for REF number '" + searchKeyNumber +
                                    "'.";
                            }
                        };

                        reader.readAsText(file);
                    } else {
                        alert("Please choose a file.");
                    }
                }

                function searchKeyword() {
                    const searchKeyNumberInput = document.getElementById('searchKeyNumberInput');
                    const fileInput = document.getElementById('fileInput');
                    const outputDiv = document.getElementById('output');
                    outputDiv.innerHTML = ''; // Clear previous output

                    const file = fileInput.files[0];
                    const searchKeyNumber = searchKeyNumberInput.value.trim();

                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const fileContent = e.target.result;

                            // Extract lines and filter by searchKeyNumber and specific pattern
                            const lines = fileContent.split(/\r?\n/);
                            const matchingLines = lines.filter(line => {
                                return (
                                    (line.includes(`ERROR # ${searchKeyNumber} #`)) || (line.includes(
                                            `ERROR # ${searchKeyNumber} #`) && line.toUpperCase()
                                        .includes('ERROR') && line.includes('failed')) ||
                                    (line.includes(`# ${searchKeyNumber} #`) && line.toUpperCase()
                                        .includes('INFO') && line.includes(
                                            'SETTING ERROR RESPONSE CODE'))

                                    // (line.includes(`ERROR # ${searchKeyNumber} #`) && line.toUpperCase().includes('ERROR') && line.includes('failed')) ||
                                    // (line.includes(`# ${searchKeyNumber} #`) && line.toUpperCase().includes('INFO') && line.includes('SETTING ERROR RESPONSE CODE'))


                                );
                            });

                            // Display the result
                            if (matchingLines.length > 0) {
                                outputDiv.innerText = "Here is Error in that TRX :'" + searchKeyNumber + "':\n \n" +
                                    matchingLines.join('\n');
                            } else {
                                outputDiv.innerText =
                                    "No lines containing the keyword 'ERROR' found for the search key number '" +
                                    searchKeyNumber + "'.";

                            }
                        };

                        reader.readAsText(file);
                    } else {
                        alert("Please choose a file.");
                    }
                }
                </script>
                <!-- 
                <div class="button_save">
                    <input type="submit" name="submit" value="Save" class="btn btn-info">
                </div> -->
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

            <!-- <div class="user-profile">
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

            </div> -->

        </div>
    </div>
    <script src="../script/role_check.js"></script>
    <!-- <script src="orders.js"></script> -->
    <script src="../script/index.js"></script>
</body>

</html>