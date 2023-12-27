<?php
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Admin Dashboard/admin.css">
    <link rel="stylesheet" type="text/css" href="../styles/chatbot/chat.css">
</head>

<body>
    <div class="container">
        <main>
            <div class="container-fluid h-100">
                <div class="row justify-content-center h-100">
                    <div class="col-md-8 col-xl-6 chat">
                        <div class="card background">
                            <div class="card-header msg_head">
                                <div class="d-flex bd-highlight">
                                    <div class="img_cont">
                                        <img src="http://localhost/cms_project/images/logo/logo.jpg"
                                            class="rounded-circle user_img" alt="User Image">
                                    </div>
                                    <div class="user_info">
                                        <span>ChatBot</span>
                                        <p>Ask anything</p>
                                    </div>
                                </div>
                            </div>
                            <div id="messageFormeight" class="card-body msg_card_body">
                                <!-- Your chat messages will be displayed here -->
                            </div>
                            <div class="send_message">
                                <form id="messageArea" method="post" enctype="multipart/form-data">
                                    <input type="text" id="text" name="msg" placeholder="Type your message..."
                                        autocomplete="off" class="form-control type_msg" required />
                                    <a href="../dashboard/dashboard.php">&lt; Back</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../script/chatscript.js"></script>
    <script src="../script/role_check.js"></script>
    <script src="../script/index.js"></script>
</body>

</html>