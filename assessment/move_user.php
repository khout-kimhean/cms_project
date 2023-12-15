<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

$alertType = "";
$alertMessage = "";

// Check if the ID is provided and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $id = $_GET['id'];
    // echo "ID is valid: $id";
    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $sql = "SELECT display_name,  function, role, branch, position, command, status, requester, approver, start_date, end_date FROM sumary_data WHERE id = ?";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error: " . $conn->error);
    }

    // Bind the ID parameter to the SQL statement
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($display_name, $function, $role, $branch, $position, $command, $status, $requester, $approver, $start_date, $end_date);
            $stmt->fetch();

            // Display the content (you can use the appropriate HTML tags)
        } else {
            echo "Content not found.";
        }
    } else {
        echo "Error executing the SQL query: " . $stmt->error;
    }

    $stmt->close();

} else {
    // echo "Invalid ID: ";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $display_name = $_POST['display_name'];
    $position = $_POST['position'];
    $function = $_POST['function'];
    $role = $_POST['role'];
    $branch = $_POST['branch'];
    $status = $_POST['status'];
    $requester = $_POST['requester'];
    $approver = $_POST['approver'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $command = $_POST['command'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "INSERT INTO move_user (display_name, position, function, role, branch, status, requester,  approver, start_date, end_date, command) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    // $sql = "UPDATE sumary_data SET display_name = ?, position = ?, function = ?, role = ?, branch = ?, status = ?, requester = ?, approver = ?, start_date = ?, end_date = ?, command = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("sssssssssss", $display_name, $position, $function, $role, $branch, $status, $requester, $approver, $start_date, $end_date, $command);
    // $stmt->bind_param("sssssssssssi", $display_name, $position, $function, $role, $branch, $status, $requester, $approver, $start_date, $end_date, $command, $id);

    if ($stmt->execute()) {
        $alertType = "success"; // Set success alert type
        $alertMessage = "User Moved successfully.";
    } else {
        $alertType = "danger"; // Set danger alert type
        $alertMessage = "Error User Move: " . $stmt->error;
    }
    $stmt->close();
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
    <link rel="stylesheet" type="text/css" href="../styles/assessment/move_user.css">
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
                <div class="title_h2">
                    <h2>Move User</h2>
                    <!-- <h2>New User</h2> -->
                    <form method="post" action="move_user.php">
                        <div class="column">

                            <input type="text" id="searchInput" name="display_name" required placeholder="Display Name"
                                value="<?php echo htmlspecialchars($display_name); ?>">

                            <!--     -->
                            <!-- <input type=" text" name="position" required placeholder="position"> -->
                        </div>
                        <div class="column">
                            <div class="form-list">
                                <label for="function">#Function</label>
                                <select name="function" class="form-control" id="function">
                                    <option value="CallCenter" <?php if ($function === "CallCenter")
                                        echo "selected"; ?>>CallCenter</option>
                                    <option value="Report View" <?php if ($function === "Report Viewer")
                                        echo "selected"; ?>>Report View</option>
                                    <option value="IB Admin" <?php if ($function === "IB Admin")
                                        echo "selected"; ?>>IB Admin</option>
                                    <option value="Card and EBanking" <?php if ($function === "Card and Ebanking")
                                        echo "selected"; ?>>Card and Ebanking</option>
                                    <option value="Sale" <?php if ($function === "Sale")
                                        echo "selected"; ?>>Sale</option>
                                    <option value="Teller" <?php if ($function === "Teller")
                                        echo "selected"; ?>>Teller</option>
                                    <option value="Account" <?php if ($function === "Account")
                                        echo "selected"; ?>>Account</option>
                                    <option value="Accountant" <?php if ($function === "Accountant")
                                        echo "selected"; ?>>Accountant</option>
                                    <option value="VIP" <?php if ($function === "VIP")
                                        echo "selected"; ?>>VIP</option>
                                    <option value="BM" <?php if ($function === "BM")
                                        echo "selected"; ?>>BM</option>
                                    <option value="VIP Bank" <?php if ($function === "VIP Bank")
                                        echo "selected"; ?>>VIP Bank</option>
                                    <option value="NA" <?php if ($function === "NA")
                                        echo "selected"; ?>>NA</option>
                                </select>
                            </div>
                            <div class="form-list">
                                <label for="role">#UserRole</label>
                                <select name="role" class="form-control" id="role">
                                    <option value="Maker" <?php if ($role === "Maker")
                                        echo "selected"; ?>>Maker</option>
                                    <option value="Checker" <?php if ($role === "Checker")
                                        echo "selected"; ?>>Checker</option>
                                    <option value="APPROVER" <?php if ($role === "APPROVER")
                                        echo "selected"; ?>>APPROVER</option>
                                    <option value="CREATOR" <?php if ($role === "CREATOR")
                                        echo "selected"; ?>>CREATOR</option>
                                    <option value="VIEWER" <?php if ($role === "VIEWER")
                                        echo "selected"; ?>>VIEWER</option>
                                    <option value="NA" <?php if ($role === "NA")
                                        echo "selected"; ?>>NA</option>
                                </select>
                            </div>
                        </div>
                        <div class="column">
                            <div class="form-list">
                                <label for="branch"># Branch :</label>
                                <select name="branch" class="form-control" id="branch"
                                    onchange="updatePositionDropdown()">
                                    <option value="Marketing and Communication Department" <?php if ($branch === "Marketing and Communication Department")
                                        echo "selected"; ?>>Marketing and
                                        CommunicationDepartment</option>
                                    <option value="Audit Department" <?php if ($branch === "Audit Department")
                                        echo "selected"; ?>>Audit Department</option>
                                    <option value="IT Department" <?php if ($branch === "IT Department")
                                        echo "selected"; ?>>IT Department</option>
                                    <option value="Digital Banking Department" <?php if ($branch === "Digital Banking Department")
                                        echo "selected"; ?>>Digital Banking Department</option>
                                    <option value="Retail" <?php if ($branch === "Retail")
                                        echo "selected"; ?>>Retail
                                    </option>
                                    <option value="Accounting and Finance Department" <?php if ($branch === "Accounting and Finance Department")
                                        echo "selected"; ?>>Accounting and Finance Department
                                    </option>
                                    <option value="Management Information System" <?php if ($branch === "Management Information System")
                                        echo "selected"; ?>>Management Information System</option>
                                    <option value="TREASURY" <?php if ($branch === "TREASURY")
                                        echo "selected"; ?>>TREASURY
                                    </option>
                                    <option value="SUPPORT-OPS " <?php if ($branch === "SUPPORT-OPS")
                                        echo "selected"; ?>>
                                        SUPPORT-OPS </option>
                                    <option value="AMB Branch" <?php if ($branch === "AMB Branch")
                                        echo "selected"; ?>>AMB
                                        Branch</option>
                                    <option value="Sen Sok Branch" <?php if ($branch === "Sen Sok Branch")
                                        echo "selected"; ?>>Sen Sok Branch</option>
                                    <option value="KMS BRANCH" <?php if ($branch === "KMS BRANCH")
                                        echo "selected"; ?>>KMS
                                        BRANCH</option>
                                    <option value="Bak Touk Branch" <?php if ($branch === "Bak Touk Branch")
                                        echo "selected"; ?>>Bak Touk Branch</option>
                                    <option value="Hengly Branch" <?php if ($branch === "Hengly Branch")
                                        echo "selected"; ?>>Hengly Branch</option>
                                    <option value="Orkide 2004 Branch" <?php if ($branch === "Orkide 2004 Branch")
                                        echo "selected"; ?>>Orkide 2004 Branch</option>
                                    <option value="Takhmao Branch" <?php if ($branch === "Takhmao Branch")
                                        echo "selected"; ?>>Takhmao Branch</option>
                                    <option value="Royal University of Phnom Penh Branch" <?php if ($branch === "Royal University of Phnom Penh")
                                        echo "selected"; ?>>Royal University of Phnom Penh
                                        Branch</option>
                                    <option value="Chbar Ampov Branch" <?php if ($branch === "Chbar Ampov Branch")
                                        echo "selected"; ?>>Chbar Ampov Branch</option>
                                    <option value="Samdechpan Branch " <?php if ($branch === "Samdechpan Branch ")
                                        echo "selected"; ?>>Samdechpan Branch </option>
                                    <option value="SHV Pshar Ler BRANCH" <?php if ($branch === "SHV Pshar Ler BRANCH")
                                        echo "selected"; ?>>SHV Pshar Ler BRANCH</option>
                                    <option value="SHV Port Branch" <?php if ($branch === "SHV Port Branch")
                                        echo "selected"; ?>>SHV Port Branch</option>
                                    <option value="Mao Tse Toung Branch" <?php if ($branch === "Mao Tse Toung Branch")
                                        echo "selected"; ?>>Mao Tse Toung Branch</option>
                                    <option value="Phnom Penh Port Branch " <?php if ($branch === "Phnom Penh Port Branch")
                                        echo "selected"; ?>>Phnom Penh Port Branch </option>
                                    <option value="Siem Reap Branch" <?php if ($branch === "Siem Reap Branch")
                                        echo "selected"; ?>>Siem Reap Branch</option>
                                    <option value="Toulkork Branch" <?php if ($branch === "Toulkork Branch")
                                        echo "selected"; ?>>Toulkork Branch</option>
                                    <option value="Battambank Branch" <?php if ($branch === "Battambank Branch")
                                        echo "selected"; ?>>Battambank Branch</option>
                                    <option value="Phsa Thmey Branch" <?php if ($branch === "Phsa Thmey Branch")
                                        echo "selected"; ?>>Phsa Thmey Branch</option>
                                    <option value="KampongCham Branch" <?php if ($branch === "KampongCham Branch")
                                        echo "selected"; ?>>KampongCham Branch</option>
                                    <option value="LEGAL" <?php if ($branch === "LEGAL")
                                        echo "selected"; ?>>LEGAL</option>
                                    <option value="COMPLIANT" <?php if ($branch === "COMPLIANT")
                                        echo "selected"; ?>>
                                        COMPLIANT</option>
                                    <option value="Priority Credit Client Office" <?php if ($branch === "Priority Credit Client Office")
                                        echo "selected"; ?>>Priority Credit Client Office</option>
                                    <option value="Collection & Recovery Office" <?php if ($branch === "Collection & Recovery Office")
                                        echo "selected"; ?>>Collection & Recovery Office</option>
                                    <option value="Credit Administration Office" <?php if ($branch === "Credit Administration Office")
                                        echo "selected"; ?>>Credit Administration Office
                                    </option>
                                    <option value="FI & Corporate Banking Department" <?php if ($branch === "FI & Corporate Banking Department")
                                        echo "selected"; ?>>FI & Corporate Banking
                                        Department
                                    </option>
                                    <option value="Business Division" <?php if ($branch === "Business Division")
                                        echo "selected"; ?>>Business Division</option>
                                    <option value="SME & Consumer Banking Department" <?php if ($branch === "SME & Consumer Banking Department")
                                        echo "selected"; ?>>SME & Consumer Banking
                                        Department
                                    </option>
                                    <option value="Human Resource" <?php if ($branch === "Human Resource")
                                        echo "selected"; ?>>Human Resource</option>
                                    <option value="Operational Risk" <?php if ($branch === "Operational Risk")
                                        echo "selected"; ?>>Operational Risk</option>
                                    <option value="INTERNATIONAL" <?php if ($branch === "INTERNATIONAL")
                                        echo "selected"; ?>>INTERNATIONAL</option>
                                    <option value="MANAGEMENT" <?php if ($branch === "MANAGEMENT")
                                        echo "selected"; ?>>
                                        MANAGEMENT</option>
                                    <option value="ADMIN" <?php if ($branch === "ADMIN")
                                        echo "selected"; ?>>ADMIN</option>
                                    <option value="Business Development " <?php if ($branch === "Business Development")
                                        echo "selected"; ?>>Business Development </option>
                                    <option value="VIP BANKING" <?php if ($branch === "VIP BANKING")
                                        echo "selected"; ?>>VIP
                                        BANKING</option>
                                </select>
                            </div>
                            <div class="form-list">
                                <label for="position"># Position</label>
                                <select name="position" class="form-control" id="position">
                                </select>
                            </div>
                            <script src="../script/droplist.js"></script>
                            <div class="form-list">
                                <label for="status"># Status :</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="Active" <?php if ($status === "Active")
                                        echo "selected"; ?>>Active
                                    </option>
                                    <option value="inactive" <?php if ($status === "inactive")
                                        echo "selected"; ?>>Inactive
                                    </option>
                                    <option value="Deactive" <?php if ($status === "Deactive")
                                        echo "selected"; ?>>Deactive
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="column">
                            <input type="text" name="requester" required placeholder="Enter Requester"
                                value="<?php echo htmlspecialchars($requester); ?>">
                            <input type="text" name="approver" required placeholder="Enter Approver"
                                value="<?php echo htmlspecialchars($approver); ?>">

                            <div class="calendar-container">
                                <label for="status">start-date</label>
                                <div>
                                    <select id="startDateDateSelect"></select>
                                </div>
                                <div>
                                    <select id="startDateMonthSelect"></select>
                                </div>
                                <div>
                                    <select id="startDateYearSelect"></select>
                                </div>
                            </div>

                            <div class="calendar-container">
                                <label for="status">End-date :</label>
                                <div>
                                    <select id="endDateDateSelect"></select>
                                </div>
                                <div>
                                    <select id="endDateMonthSelect"></select>
                                </div>
                                <div>
                                    <select id="endDateYearSelect"></select>
                                </div>
                            </div>


                            <input type="text" name="command" required placeholder="Enter Command"
                                value="<?php echo htmlspecialchars($command); ?>">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">

                            <input type="submit" name="submit" value="Move" class="form-btn">
                            <div class="back_button">
                                <!-- <a href="../templates/search_move.php" class="back-button">
                                    <i class="fa fa-chevron-circle-left" style="font-size: 25px">Back</i>
                                </a> -->
                            </div>
                        </div>
                    </form>
                    <div class="alert alert-<?php echo $alertType; ?> text-center">
                        <?php echo $alertMessage; ?>
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
                        <small class="text-muted">Admin</small>
                    </div>
                    <div class="profile-photo">
                        <img src="../images/logo/logo.jpg">
                    </div>
                </div>

            </div>

            <div class="user-profile">
                <div class="logo">
                    <img src="../images/logo/logo.jpg">
                    <h2>FTB Bank </h2>
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Function to populate the dropdowns
            function populateDropdowns(dateSelect, monthSelect, yearSelect) {
                // Populate the date dropdown
                for (let i = 1; i <= 31; i++) {
                    const option = document.createElement("option");
                    option.text = i;
                    dateSelect.add(option);
                }

                // Populate the month dropdown
                const months = [
                    "January", "February", "March", "April",
                    "May", "June", "July", "August",
                    "September", "October", "November", "December"
                ];
                for (let i = 0; i < months.length; i++) {
                    const option = document.createElement("option");
                    option.text = months[i];
                    monthSelect.add(option);
                }

                // Populate the year dropdown (adjust the range as needed)
                const currentYear = new Date().getFullYear();
                for (let i = currentYear - 25; i <= currentYear + 25; i++) {
                    const option = document.createElement("option");
                    option.text = i;
                    yearSelect.add(option);
                }
            }

            // Populate the start date dropdowns
            const startDateDateSelect = document.getElementById("startDateDateSelect");
            const startDateMonthSelect = document.getElementById("startDateMonthSelect");
            const startDateYearSelect = document.getElementById("startDateYearSelect");

            populateDropdowns(startDateDateSelect, startDateMonthSelect, startDateYearSelect);

            // Populate the end date dropdowns
            const endDateDateSelect = document.getElementById("endDateDateSelect");
            const endDateMonthSelect = document.getElementById("endDateMonthSelect");
            const endDateYearSelect = document.getElementById("endDateYearSelect");

            populateDropdowns(endDateDateSelect, endDateMonthSelect, endDateYearSelect);
        });
    </script>
    <!-- <script src="orders.js"></script> -->
    <script src="../script/index.js"></script>
</body>

</html>