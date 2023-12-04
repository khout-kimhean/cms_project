<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

$alertType = ""; // Define the alert type (success or danger)
$alertMessage = ""; // Define the alert message


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query the database to retrieve the content based on the ID
    $sql = "SELECT display_name, brunch,position, function, role, status ,requester, approver,start_date,end_date,comment FROM sumary WHERE id = ?";

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
            $stmt->bind_result($display_name, $brunch, $position, $function, $role, $status, $requester, $approver, $start_date, $end_date, $comment);
            $stmt->fetch();
            // Display the content (you can use the appropriate HTML tags)
        } else {
            echo "Content not found.";
        }
    } else {
        echo "Error executing the SQL query: " . $stmt->error;
    }

    // $stmt->close();
    // $conn->close();
} else {
    echo "Invalid ID.";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $display_name = $_POST['display_name'];
    $position = $_POST['position'];
    $brunch = $_POST['brunch'];
    $function = $_POST['function'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $requester = $_POST['requester'];
    $approver = $_POST['approver'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $comment = $_POST['comment'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE tbl_create_user SET display_name = ?, brunch =? ,position = ?, function =? ,role =?, status = ?,requester =?, approver =?, start_date =?, end_date =? ,comment =? WHERE id = ? ";
    $stmt = $conn->prepare($sql);


    if ($stmt === false) {
        die("Error: " . $conn->error);
    }

    $stmt->bind_param("sssssssssss", $display_name, $brunch, $position, $function, $role, $status, $requester, $approver, $start_date, $end_date, $comment);

    if ($stmt->execute()) {
        $alertType = "success"; // Set success alert type
        $alertMessage = "Data updated successfully.";
    } else {
        $alertType = "danger"; // Set danger alert type
        $alertMessage = "Error updating data: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}


?>



<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Admin Dashboard/styles/resign_assessment.css">
    <title>Admin Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script>
        function formatDateInput(inputField) {
            // Get the user input
            let userInput = inputField.value;

            // Remove any non-numeric characters
            userInput = userInput.replace(/\D/g, '');
            // Check if the input length is at least 6 characters
            if (userInput.length >= 6) {
                // Format the date as MM/DD/YY
                const formattedDate = userInput.replace(/(\d{2})(\d{2})(\d{2})/, '$1/$2/$3');
                inputField.value = formattedDate;
            }
        }
    </script>
    <style>

    </style>
    <script src="../tinymce/tinymce.min.js"></script>

    <script>
        tinymce.init({
            selector: '#myTextarea',
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            toolbar_sticky: true,
            autosave_ask_before_unload: true,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            image_advtab: true,
            link_list: [
                { title: 'My page 1', value: 'https://www.codexworld.com' },
                { title: 'My page 2', value: 'http://www.codexqa.com' }
            ],
            image_list: [
                { title: 'My page 1', value: 'https://www.codexworld.com' },
                { title: 'My page 2', value: 'http://www.codexqa.com' }
            ],
            image_class_list: [
                { title: 'None', value: '' },
                { title: 'Some class', value: 'class-name' }
            ],
            importcss_append: true,
            file_picker_callback: (callback, value, meta) => {
                / Provide file and text for the link dialog /
                if (meta.filetype === 'file') {
                    callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
                }

                / Provide image and alt text for the image dialog /
                if (meta.filetype === 'image') {
                    callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
                }

                / Provide alternative source and posted for the media dialog /
                if (meta.filetype === 'media') {
                    callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
                }
            },
            templates: [
                { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
                { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
                { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
            ],
            template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
            height: 400,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image table',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });
    </script>
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
                    <h3>View File</h3>
                </a>
                <a href="../templates/query_tb.php">
                    <span class="material-icons-sharp">
                        report_gmailerrorred
                    </span>
                    <h3>Query Data</h3>
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
        <main>
            <div class="form-container">
                <form action="" method="post" id="create_user_form"> <!-- Add the id attribute to the form -->
                    <div class="form-back">
                        <a href="../templates/user_assessment.php?id=" class="back-button">
                            <i class="fa fa-chevron-circle-left" style="font-size: 25px">Back</i>
                        </a>
                    </div>
                    <h3> User Resign</h3>
                    <div class="form-b">
                        <label for="display_name"># Name</label>
                        <input type="text" name="display_name" value="<?php echo htmlspecialchars($display_name); ?>"
                            id="display_name">
                    </div>
                    <div class="form-list">
                        <div class="select-container">
                            <label for="brunch"># Brunch </label>
                            <select name="brunch" class="form-control" id="brunch" onchange="updatePositionDropdown()">

                                <option value="Marketing and Communication Department" <?php if ($brunch === "Marketing and CommunicationDepartment")
                                    echo "selected"; ?>>Marketing and
                                    CommunicationDepartment</option>

                                <option value="Audit Department" <?php if ($brunch === "Audit Department")
                                    echo "selected"; ?>>Audit Department</option>

                                <option value="IT Department" <?php if ($brunch === "IT Departmentt")
                                    echo "selected"; ?>>IT Department</option>

                                <option value="Digital Banking Department" <?php if ($brunch === "Digital Banking Department")
                                    echo "selected"; ?>>Digital Banking Department</option>

                                <option value="Retail" <?php if ($brunch === "Retail")
                                    echo "selected"; ?>>Retail</option>

                                <option value="Accounting and Finance Department" <?php if ($brunch === "Accounting and Finance Department")
                                    echo "selected"; ?>>Accounting and Finance Department</option>

                                <option value="Management Information System" <?php if ($brunch === "Management Information System")
                                    echo "selected"; ?>>Management Information System</option>

                                <option value="TREASURY" <?php if ($brunch === "TREASURY")
                                    echo "selected"; ?>>TREASURY</option>

                                <option value="SUPPORT-OPS " <?php if ($brunch === "SUPPORT-OPS")
                                    echo "selected"; ?>>SUPPORT-OPS </option>

                                <option value="AMB Branch" <?php if ($brunch === "AMB Branch")
                                    echo "selected"; ?>>AMB Branch</option>

                                <option value="Sen Sok Branch" <?php if ($brunch === "Sen Sok Branch")
                                    echo "selected"; ?>>Sen Sok Branch</option>

                                <option value="KMS BRANCH" <?php if ($brunch === "KMS BRANCH")
                                    echo "selected"; ?>>KMS BRANCH</option>

                                <option value="Bak Touk Branch" <?php if ($brunch === "Bak Touk Branch")
                                    echo "selected"; ?>>Bak Touk Branch</option>

                                <option value="Hengly Branch" <?php if ($brunch === "Hengly Branch")
                                    echo "selected"; ?>>Hengly Branch</option>

                                <option value="Orkide 2004 Branch" <?php if ($brunch === "Orkide 2004 Branch")
                                    echo "selected"; ?>>Orkide 2004 Branch</option>

                                <option value="Takhmao Branch" <?php if ($brunch === "Takhmao Branch")
                                    echo "selected"; ?>>Takhmao Branch</option>

                                <option value="Royal University of Phnom Penh Branch" <?php if ($brunch === "Royal University of Phnom Penh")
                                    echo "selected"; ?>>Royal University of Phnom Penh
                                    Branch</option>

                                <option value="Chbar Ampov Branch" <?php if ($brunch === "Chbar Ampov Branch")
                                    echo "selected"; ?>>Chbar Ampov Branch</option>

                                <option value="Samdechpan Branch " <?php if ($brunch === "Samdechpan Branch")
                                    echo "selected"; ?>>Samdechpan Branch </option>

                                <option value="SHV Pshar Ler BRANCH" <?php if ($brunch === "SHV Pshar Ler BRANCH")
                                    echo "selected"; ?>>SHV Pshar Ler BRANCH</option>
                                <option value="SHV Port Branch" <?php if ($brunch === "SHV Port Branch")
                                    echo "selected"; ?>>SHV Port Branch</option>

                                <option value="Mao Tse Toung Branch" <?php if ($brunch === "Mao Tse Toung Branch")
                                    echo "selected"; ?>>Mao Tse Toung Branch</option>

                                <option value="Phnom Penh Port Branch " <?php if ($brunch === "Phnom Penh Port Branch")
                                    echo "selected"; ?>>Phnom Penh Port Branch </option>

                                <option value="Siem Reap Branch" <?php if ($brunch === "Siem Reap Branch")
                                    echo "selected"; ?>>Siem Reap Branch</option>

                                <option value="Toulkork Branch" <?php if ($brunch === "Toulkork Branch")
                                    echo "selected"; ?>>Toulkork Branch</option>

                                <option value="Battambank Branch" <?php if ($brunch === "Battambank Branch")
                                    echo "selected"; ?>>Battambank Branch</option>

                                <option value="Phsa Thmey Branch" <?php if ($brunch === "Phsa Thmey Branch")
                                    echo "selected"; ?>>Phsa Thmey Branch</option>

                                <option value="KampongCham Branch" <?php if ($brunch === "KampongCham Branch")
                                    echo "selected"; ?>>KampongCham Branch</option>

                                <option value="LEGAL" <?php if ($brunch === "LEGAL")
                                    echo "selected"; ?>>LEGAL</option>

                                <option value="COMPLIANT" <?php if ($brunch === "COMPLIANT")
                                    echo "selected"; ?>>COMPLIANT</option>

                                <option value="Priority Credit Client Office" <?php if ($brunch === "Priority Credit Client Office")
                                    echo "selected"; ?>>Priority Credit Client Office</option>

                                <option value="Collection & Recovery Office" <?php if ($brunch === "Collection & Recovery Office")
                                    echo "selected"; ?>>Collection & Recovery Office</option>

                                <option value="Credit Administration Office" <?php if ($brunch === "Credit Administration Office")
                                    echo "selected"; ?>>Credit Administration Office</option>

                                <option value="FI & Corporate Banking Department" <?php if ($brunch === "FI & Corporate Banking Department")
                                    echo "selected"; ?>>FI & Corporate Banking Department
                                </option>

                                <option value="Business Division" <?php if ($brunch === "Business Division")
                                    echo "selected"; ?>>Business Division</option>

                                <option value="SME & Consumer Banking Department" <?php if ($brunch === "SME & Consumer Banking Department")
                                    echo "selected"; ?>>SME & Consumer Banking Department
                                </option>

                                <option value="Human Resource" <?php if ($brunch === "Human Resource")
                                    echo "selected"; ?>>Human Resource</option>
                                <option value="Operational Risk" <?php if ($brunch === "Operational Risk")
                                    echo "selected"; ?>>Operational Risk</option>
                                <option value="INTERNATIONAL" <?php if ($brunch === "INTERNATIONAL")
                                    echo "selected"; ?>>INTERNATIONAL</option>
                                <option value="MANAGEMENT" <?php if ($brunch === "MANAGEMENT")
                                    echo "selected"; ?>>MANAGEMENT</option>
                                <option value="ADMIN" <?php if ($brunch === "ADMIN")
                                    echo "selected"; ?>>ADMIN</option>
                                <option value="Business Development " <?php if ($brunch === "Business Development")
                                    echo "selected"; ?>>Business Development </option>
                                <option value="VIP BANKING" <?php if ($brunch === "VIP BANKING")
                                    echo "selected"; ?>>VIP BANKING</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-list">
                        <div class="select-container">
                            <label for="position"># Position</label>
                            <select name="position" class="form-control" id="position"></select>
                        </div>
                    </div>
                    <div class="form-list">
                        <div class="select-container">
                            <label for="function"># Function</label>
                            <select name="function" class="form-control" id="function">
                                <option value="NA" <?php if ($function === "NA")
                                    echo "selected"; ?>>NA</option>
                                <option value="Sale" <?php if ($function === "Sale")
                                    echo "selected"; ?>>Sale</option>
                                <option value="Call Center" <?php if ($function === "Call Center")
                                    echo "selected"; ?>>
                                    Call Center</option>
                                <option value="Report Viewer" <?php if ($function === "Report Viewer")
                                    echo "selected"; ?>>Report Viewer</option>
                                <option value="IB Admin" <?php if ($function === "IB Admin")
                                    echo "selected"; ?>>IB Admin
                                </option>
                                <option value="Card And Banking" <?php if ($function === "Card and Ebanking")
                                    echo "selected"; ?>>Card and Ebanking</option>
                                <option value="Teller" <?php if ($function === "Teller")
                                    echo "selected"; ?>>Teller
                                </option>
                                <option value="Account" <?php if ($function === "Account")
                                    echo "selected"; ?>>Account
                                </option>
                                <option value="VIP" <?php if ($function === "VIP")
                                    echo "selected"; ?>>VIP</option>
                                <option value="BM" <?php if ($function === "BM")
                                    echo "selected"; ?>>BM</option>
                                <option value="VIP Bank" <?php if ($function === "VIP Bank")
                                    echo "selected"; ?>>VIP Bank
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-list">
                        <div class="select-container">
                            <label for="role"># Role</label>
                            <select name="role" class="form-control" id="role">
                                <option value="NA" <?php if ($role === "NA")
                                    echo "selected"; ?>>NA</option>
                                <option value="Checker" <?php if ($role === "Checker")
                                    echo "selected"; ?>>Checker
                                </option>
                                <option value="Maker" <?php if ($role === "Maker")
                                    echo "selected"; ?>>Maker</option>
                                <option value="Viewer" <?php if ($role === "Viewer")
                                    echo "selected"; ?>>Viewer</option>
                                <option value="Creator" <?php if ($role === "Creator")
                                    echo "selected"; ?>>Creator
                                </option>
                                <option value="Approver" <?php if ($role === "Approver")
                                    echo "selected"; ?>>Approver
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-list">
                        <div class="select-container">
                            <label for="status"># Status</label>
                            <select name="status" class="form-control" id="status">
                                <option value="active" <?php if ($status === "active")
                                    echo "selected"; ?>>active</option>
                                <option value="inactive" <?php if ($status === "inactive")
                                    echo "selected"; ?>>inactive
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-container">
                        <div class="form-b">
                            <label for="status"># Requester</label>
                            <input type="text" name="requester" value="<?php echo htmlspecialchars($requester); ?>"
                                id="approver">
                        </div>
                        <div class="form-b">
                            <label for="status"># Approver</label>
                            <input type="text" name="approver" value="<?php echo htmlspecialchars($approver); ?>"
                                id="approver">
                        </div>
                        <input type="text" name="start_date" required placeholder="start date       dd/mm/yy"
                            oninput="formatDateInput(this);">
                        <input type="text" name="end_date" required placeholder="end date       dd/mm/yy"
                            oninput="formatDateInput(this);">
                        <div class="form-b">
                            <label for="status"># Comment</label>
                            <input type="text" name="comment" value="<?php echo htmlspecialchars($comment); ?>"
                                id="comment">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Save" class="form-btn">
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
                        <img src="../Admin Dashboard/images/profile.jpg">
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

    <script src="../Admin Dashboard/index.js"></script>
    <script src="../Admin Dashboard/list.js"></script>
</body>

</html>