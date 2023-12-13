<?php
require '../vendor/autoload.php';

$host = "localhost";
$user = "root";
$pass = "";
$db = "demo";

// Create a connection to the database
$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $allowedExtensions = ['pdf', 'txt', 'doc', 'docx', 'png', 'jpg', 'jpeg', 'gif', 'xlsx', 'xls'];
    $allowedMimeTypes = [
        'application/pdf',
        'text/plain',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/png',
        'image/jpeg',
        'image/gif',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel',
    ];

    // Check if a file was uploaded
    if (!empty($_FILES['file1']['name'])) {
        $filename = $_FILES['file1']['name'];
        $fileTmpName = $_FILES['file1']['tmp_name'];
        $fileExt = pathinfo($filename, PATHINFO_EXTENSION);

        if (!in_array($fileExt, $allowedExtensions) && !in_array($_FILES['file1']['type'], $allowedMimeTypes)) {
            $errorMessage = "Invalid File Extension or MIME Type!";

            // Debugging information
            echo "Debugging Information:<br>";
            echo "File Extension: $fileExt<br>";
            echo "MIME Type: " . $_FILES['file1']['type'] . "<br>";

            header("Location: data_store.php?st=error&msg=" . urlencode($errorMessage));
            exit;
        }

        $uploadDirectory = 'uploads/';

        if (!is_dir($uploadDirectory)) {
            if (!mkdir($uploadDirectory, 0777, true)) {
                die('Failed to create the "uploads" directory.');
            }
        }

        $sql = 'SELECT MAX(id) as id FROM data_store';
        $result = mysqli_query($con, $sql);
        $newFilename = '';

        if ($result && $result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            $newFilename = $filename;
        } else {
            $newFilename = '1-' . $filename;
        }

        $filePath = $uploadDirectory . $newFilename . '.' . $fileExt;

        if (move_uploaded_file($fileTmpName, $filePath)) {
            $date_upload = date('Y-m-d H:i:s'); // Changed the date format
            $shortDescription = strip_tags($_POST['short_description']); // Remove HTML tags
            $title = $_POST['title']; // Provide a title if needed
            $drop_file = $_POST['drop_file'];
        } else {
            $errorMessage = "Error moving uploaded file.";
            header("Location: data_store.php?st=error&msg=" . urlencode($errorMessage));
            exit;
        }
    } else {
        // If no file was uploaded, set filename to blank
        $newFilename = '';
        $date_upload = date('Y-m-d H:i:s'); // Changed the date format
        $shortDescription = strip_tags($_POST['short_description']); // Remove HTML tags
        $title = $_POST['title']; // Provide a title if needed
        $drop_file = $_POST['drop_file'];
    }

    $sql = "INSERT INTO data_store (filename, date_upload, drop_file, short_description, title) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $newFilename, $date_upload, $drop_file, $shortDescription, $title);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: data_store.php?st=success");
        exit;
    } else {
        $errorMessage = "Error: " . mysqli_error($con);
        header("Location: data_store.php?st=error&msg=" . urlencode($errorMessage));
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta content="width=device-width, initial-scale=1.0" name="viewport"> -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" type="text/css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/data_store/data_store.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
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
        link_list: [{
                title: 'My page 1',
                value: 'https://www.test.com'
            },
            {
                title: 'My page 2',
                value: 'http://www.test.com'
            }
        ],
        image_list: [{
                title: 'My page 1',
                value: 'https://www.test.com'
            },
            {
                title: 'My page 2',
                value: 'http://www.test.com'
            }
        ],
        image_class_list: [{
                title: 'None',
                value: ''
            },
            {
                title: 'Some class',
                value: 'class-name'
            }
        ],
        importcss_append: true,
        file_picker_callback: (callback, value, meta) => {
            /* Provide file and text for the link dialog */
            if (meta.filetype === 'file') {
                callback('https://www.google.com/logos/google.jpg', {
                    text: 'My text'
                });
            }

            /* Provide image and alt text for the image dialog */
            if (meta.filetype === 'image') {
                callback('https://www.google.com/logos/google.jpg', {
                    alt: 'My alt text'
                });
            }

            /* Provide alternative source and posted for the media dialog */
            if (meta.filetype === 'media') {
                callback('movie.mp4', {
                    source2: 'alt.ogg',
                    poster: 'https://www.google.com/logos/google.jpg'
                });
            }
        },
        templates: [{
                title: 'New Table',
                description: 'creates a new table',
                content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
            },
            {
                title: 'Starting my story',
                description: 'A cure for writers block',
                content: 'Once upon a time...'
            },
            {
                title: 'New list with dates',
                description: 'New List with dates',
                content: '<div class="mceTmpl"><span class="cdate">cdate</span><br><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
            }
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
                <a href="../contact/contact.php">
                    <span class="fa fa-address-card">
                    </span>
                    <h3>Contact</h3>
                </a>
                <a href="../data_store/data_mgt.php" class="active">
                    <span class="fa fa-upload">
                    </span>
                    <h3>Data Store</h3>
                </a>


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
                <a href="../to_do_list/todo_management.php">
                    <span class="fa fa-list-alt">
                    </span>
                    <h3>To-do List</h3>
                </a>
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
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">

                            <div class="card-body">
                                <form action="data_store.php" method="post" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="file1">Select File to Upload:</label>
                                        <input type="file" name="file1" class="form-control-file" id="file1">
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Title:</label>
                                        <input type="text" name="title" class="form-control" id="title">
                                    </div>
                                    <div class="form-list">
                                        <label for="drop_file">Select:</label>
                                        <select name="drop_file" class="form-control" id="drop_file">
                                            <option value="Team Card Payment">Team Card Payment</option>
                                            <option value="Team ATM">Team ATM</option>
                                            <option value="Team Digital Branch">Team Digital Branch</option>
                                        </select>
                                    </div>
                                    <textarea id="myTextarea" name="short_description"></textarea>
                                    <div class="form-group">
                                        <input type="submit" name="submit" value="Save" class="btn btn-info">
                                    </div>

                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($_GET['st']) && $_GET['st'] === 'error') { ?>
            <div class="alert alert-danger text-center">
                <?php echo isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : 'An error occurred.'; ?>
            </div>
            <?php } ?>
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