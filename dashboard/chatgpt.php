<?php
include '../connect/conectdb.php';
include '../connect/role_access.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Chatbot</title>
    <link rel="stylesheet" href="../dashboard/chatgpt.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="../script/chatgpt.js" defer></script>
    <script src="../script/role_check.js" defer></script> <!-- Include role_check.js here -->
</head>

<body <?php echo $_SESSION['user_name']; ?>>
    <div class="chat-container"></div>
    <div class="typing-container">
        <div class="typing-content">
            <!-- <?php echo $_SESSION['user_name']; ?> -->
            <div class="typing-textarea">
                <textarea id="chat-input" spellcheck="false" placeholder="Message ChatBot . . ." required></textarea>
                <span id="send-btn" class="material-symbols-rounded">send</span>
            </div>
            <div class="typing-controls">
                <a href="../dashboard/dashboard.php">
                    <span id="back-btn" class="material-symbols-rounded">
                        <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>
                    </span>
                </a>
                <span id="theme-btn" class="material-symbols-rounded">light_mode</span>
                <span id="delete-btn" class="material-symbols-rounded">delete</span>
            </div>
        </div>
    </div>
</body>

</html>