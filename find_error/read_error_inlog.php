<?php
// session_start();

// Include the file with the access check
include '../dashboard/check_access.php';

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
    <!-- <link rel="stylesheet" type="text/css" href="../Admin Dashboard/styles/todo_management.css"> -->
    <link rel="stylesheet" type="text/css" href="../styles/find_error/read_errorinlog.css">
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
                <a href="../assessment/assessment.php">
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
            <div class="container2">
                <div class="head">
                    <h2>Read Error in log File</h2>
                </div>
                <div class="item">
                    <input class="file" type="file" id="fileInput" />
                    <input class="search" type="text" id="searchKeyNumberInput"
                        placeholder="Enter REF-No Here . . . . . . . " />
                    <button onclick="processFile()">Show </button>
                    <button onclick="searchKeyword()">Show Detail</button>
                    <br />
                </div>
                <!-- <button onclick="processFile()">Show </button> -->
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

                            reader.onload = function (e) {
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
                                    outputDiv.innerText = "No log entry found for REF number " + searchKeyNumber + ".";
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

                            reader.onload = function (e) {
                                const fileContent = e.target.result;

                                // Extract lines and filter by searchKeyNumber and specific pattern
                                const lines = fileContent.split(/\r?\n/);
                                let capturingXml = false;
                                const matchingLines = [];
                                const xmlLines = [];

                                for (let i = 0; i < lines.length; i++) {
                                    const line = lines[i];

                                    // Replace the condition to check for the specific keyword
                                    if (line.includes(`INFO  # ${searchKeyNumber} # SETTING ERROR RESPONSE CODE`)) {
                                        capturingXml = true;

                                        // Go up 20 lines and start capturing
                                        const startIndex = Math.max(0, i - 18);
                                        for (let j = startIndex; j < i; j++) {
                                            if (
                                                !lines[j].includes('RESPONSE CODE FROM ARQC VERIFY REPLY') &&
                                                !lines[j].includes('------------------------------------') &&
                                                !lines[j].includes('CLOSING SOCKET') &&
                                                !lines[j].includes(
                                                    'CLOSE THE SOCKET TO CASTOR ######################### ............'
                                                ) &&
                                                !lines[j].includes('CONNETING AGAIN') &&
                                                !lines[j].includes('FAILURE IN SENDING MESSAGE!!!!') &&
                                                !lines[j].includes('----------------------------') &&
                                                !lines[j].includes('NtwkProfileHandler::getBackupRouteProfile') &&

                                                lines[j].trim() !== ''
                                            ) {
                                                matchingLines.push(lines[j]);
                                            }
                                        }
                                    } else if (capturingXml) {
                                        // Check for the end pattern
                                        if (line.includes('')) {
                                            capturingXml = false;
                                            break;
                                        }
                                    }
                                }

                                // Display the result for general information
                                if (matchingLines.length > 0) {
                                    outputDiv.innerText = "*Here is Detail of that TRX :\n\n" + matchingLines.join(
                                        '\n');
                                } else {
                                    outputDiv.innerText = "*Don't have error in ARQC Verify.";
                                }

                                // Extract lines and filter by searchKeyNumber and specific pattern for errors
                                const errorLines = lines.filter(line => {
                                    return (
                                        (line.includes(`ERROR # ${searchKeyNumber} #`) && line.includes(
                                            'failed')) ||
                                        (line.includes(`ERROR # ${searchKeyNumber} #`) && line.toUpperCase()
                                            .includes('FAILED') && line.includes('CONNECT')) ||
                                        (line.includes(`# ${searchKeyNumber} #`) && line.toUpperCase()
                                            .includes('INFO') && line.includes(
                                                'SETTING ERROR RESPONSE CODE'))
                                    );
                                });

                                // Display the result for errors
                                if (errorLines.length > 0) {
                                    outputDiv.innerText += "\n\n*Here is Error of that TRX :'" + searchKeyNumber +
                                        "':\n \n" + errorLines.join('\n');
                                } else {
                                    outputDiv.innerText += "\n\n*No Error Found for that TRX'" + searchKeyNumber + "'.";
                                }


                                capturingXml = false;
                                let xmlStartIndex = -1;

                                for (let i = 0; i < lines.length; i++) {
                                    const line = lines[i];

                                    // Replace the condition to check for the specific keyword
                                    if (line.includes(`INFO  # ${searchKeyNumber} # COMPLETE MSG [MASKED] :`)) {
                                        capturingXml = true;

                                        xmlStartIndex = i;
                                        while (xmlStartIndex >= 0 && !lines[xmlStartIndex].includes(
                                            'xml version="1.0" encoding="UTF-8"')) {
                                            xmlStartIndex--; // Move up one line
                                        }

                                        // Start capturing
                                        const startIndex = Math.max(0, xmlStartIndex - 0);
                                        for (let j = startIndex; j < i; j++) {
                                            if (!lines[j].includes('----POS PURCHASE TRXN VALIDATION END') &&
                                                !lines[j].includes('Reply to router')) {
                                                xmlLines.push(lines[j]);
                                            }
                                        }

                                        // Break out of the loop after capturing the XML
                                        break;
                                    }
                                }
                                for (let i = 0; i < lines.length; i++) {
                                    const line = lines[i];

                                    // Replace the condition to check for the specific keyword
                                    if (line.includes(`INFO  # ${searchKeyNumber} # READ MSG [MASKED] :`)) {
                                        capturingXml = true;

                                        xmlStartIndex = i;
                                        while (xmlStartIndex >= 0 && !lines[xmlStartIndex].includes(
                                            'xml version="1.0" encoding="UTF-8"')) {
                                            xmlStartIndex--; // Move up one line
                                        }

                                        // Start capturing
                                        const startIndex = Math.max(0, xmlStartIndex - 0);
                                        for (let j = startIndex; j < i; j++) {
                                            if (!lines[j].includes('----POS PURCHASE TRXN VALIDATION END') &&
                                                !lines[j].includes('Reply to router')) {
                                                xmlLines.push(lines[j]);
                                            }
                                        }

                                        // Break out of the loop after capturing the XML
                                        break;
                                    }
                                }

                                // Display the result for XML
                                if (xmlLines.length > 0) {
                                    outputDiv.innerText += "\n\n*Here is XML Detail of that TRX :\n\n" + xmlLines.join(
                                        '\n');
                                } else {
                                    outputDiv.innerText += "\n\n*No XML Found for that TRX'.";
                                }
                            };



                            reader.readAsText(file);
                        } else {
                            alert("Please choose a file.");
                        }
                    }
                </script>




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
                        <img src="../images/logo/logo.jpg">
                    </div>
                </div>

            </div>

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