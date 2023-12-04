<?php
// Include the PhpSpreadsheet library
require 'vendor/autoload.php';

// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'demo';

// Create a database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    if (!empty($fileName)) {
        if (in_array($fileExtension, ['xlsx', 'xls'])) {
            // Load the Excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);

            // Get the first worksheet
            $worksheet = $spreadsheet->getActiveSheet();

            // Get all the rows as an array
            $data = $worksheet->toArray();

            // Insert data into the database
            foreach ($data as $row) {
                // Assuming the Excel file has six columns (id, name, branch, position, function, role)
                $id = $row[0];
                $name = $row[1];
                $branch = $row[2];
                $position = $row[3];
                $function = $row[4];
                $role = $row[5];

                // Prepare and execute the SQL insert statement
                $sql = "INSERT INTO assessment (id, name, branch, position, function, role) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("ssssss", $id, $name, $branch, $position, $function, $role);

                    if ($stmt->execute()) {
                        echo "Data inserted successfully.<br>";
                    } else {
                        echo "Error inserting data: " . $stmt->error . "<br>";
                    }
                } else {
                    echo "Error preparing SQL statement: " . $conn->error . "<br>";
                }
            }
        } else {
            echo "Error: Please upload a valid Excel file (xlsx or xls).";
        }
    } else {
        echo "Error: File not uploaded or empty.";
    }

    // Close the database connection
    $conn->close();
}
?>
<!-- ... rest of your HTML code ... -->



<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../static/css/style_upload.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>Upload a File</h2>

        <form method="post" enctype="multipart/form-data" id="uploadForm" onsubmit="changeBackground()">
            <label for="file">Select file to Upload:</label>
            <input type="file" name="file" id="file">
            <input type="submit" name="submit" value="Upload File" id="uploadButton">
        </form>
        <div id="uploadResult" class="message"></div>
        <a href="../templates/chat.html">Go Back to Chat</a>
    </div>

    <script>
        $(document).ready(function () {
            $("#uploadForm").on("submit", function (e) {
                e.preventDefault(); // Prevent the default form submission
                // Show a message
                $("#uploadResult").text("Uploading file...");

                // Create a FormData object to send the file data
                var formData = new FormData(this);

                // Send the form data to the server using AJAX
                $.ajax({
                    type: "POST",
                    url: "upload.php", // Specify the URL to your PHP script
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        // Display the response in the uploadResult div
                        $("#uploadResult").html(response);
                        // Reset the background after the form submission is complete
                        resetBackground();
                        // Show a success message
                        $("#uploadResult").text("File uploaded successfully!");
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: " + error);
                        // Reset the background after the form submission is complete
                        resetBackground();
                        // Show an error message
                        $("#uploadResult").text("Error uploading file: " + error);
                    }
                });
            });
        });

        function changeBackground() {
            // Change the background when the form is submitted
            document.body.classList.add("background-change");
        }

        function resetBackground() {
            // Reset the background to its original state
            document.body.classList remove("background-change");
        }
    </script>
</body>
</html>
