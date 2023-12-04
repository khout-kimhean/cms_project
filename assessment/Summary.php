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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/assessment/Summary.css">
    <title>Admin Dashboard</title>
</head>

<body>
    <div class="container">
        <main>
            <span class="button-container">
                <form method="post" enctype="multipart/form-data">
                    <a href="../assessment/assessment.php" class="back-button">
                        <i class="fa fa-chevron-circle-left" style="font-size:28px">Back</i>
                    </a>
                </form>
            </span>
            <div class="container2">
                <div class="table-container">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DisplayName</th>
                                <th>Position</th>
                                <th>Function</th>
                                <th>Role</th>
                                <th>Branch</th>
                                <th>Status</th>
                                <th>Requester</th>
                                <th>Approver</th>
                                <th>StartDate</th>
                                <th>EndDate</th>
                                <th>Command</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM data_export ORDER BY id ASC";
                            $result = mysqli_query($con, $sql);

                            if ($result && mysqli_num_rows($result) > 0) {
                                $i = 1;
                                while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $i++; ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['display_name']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['position']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['function']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['role']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['branch']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['requester']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['approver']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['start_date']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['end_date']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['command']); ?>
                                        </td>


                                    </tr>
                                <?php }
                            } else {
                                echo "<tr><td colspan='7'>No files found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
</body>

</html>