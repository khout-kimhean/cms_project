<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\IOFactory as PhpWordIOFactory;
use PhpOffice\PhpWord\PhpWord;

require '../vendor/autoload.php';

if (isset($_GET['file'])) {
    // Get the file name from the query parameter
    $filename = $_GET['file'];

    // Define the directory where the files are stored
    $directory = 'uploads/';

    // Define the file path
    $filePath = $directory . $filename;

    // Check if the file exists
    if (file_exists($filePath)) {
        // Determine the content type based on the file extension
        $fileExt = pathinfo($filename, PATHINFO_EXTENSION);

        if (in_array($fileExt, ['pdf', 'png', 'jpg', 'jpeg', 'gif'])) {
            // Display images and other supported formats
            $contentType = mime_content_type($filePath);
            header("Content-Type: $contentType");
            readfile($filePath);
        } elseif (in_array($fileExt, ['txt'])) {
            // Handle text files
            $content = file_get_contents($filePath);

            echo '<!DOCTYPE html>
                <html>
                <head>
                    <title>Text Viewer</title>
                </head>
                <body>
                    <pre>' . htmlspecialchars($content) . '</pre>
                </body>
                </html>';
        }elseif (in_array($fileExt, ['docx'])) {
            // Handle Word documents (docx)
            $phpWord = PhpWordIOFactory::load($filePath);
            $htmlWriter = PhpWordIOFactory::createWriter($phpWord, 'HTML');
            $htmlWriter->save('document.html'); // Save as HTML
            readfile('document.html');

        } elseif (in_array($fileExt, ['xlsx', 'xls'])) {
            $reader = IOFactory::createReader('Xlsx');
            if ($fileExt === 'xls') {
                $reader = IOFactory::createReader('Xls');
            }
            $spreadsheet = $reader->load($filePath);

            echo '<!DOCTYPE html>
                <html>
                <head>
                    <title>Excel Viewer</title>
                    <style>
                        body {
                            margin-top: 50px;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }

                        th, td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: left;
                        }

                        th {
                            background-color: #f2f2f2;
                        }

                        tr:nth-child(even) {
                            background-color: #f2f2f2;
                        }

                        tr:hover {
                            background-color: #e0e0e0;
                        }
                    </style>
                </head>
                <body>';

            echo '<table>';
            foreach ($spreadsheet->getActiveSheet()->toArray() as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td>' . htmlspecialchars($cell) . '</td>'; // Added htmlspecialchars to escape HTML
                }
                echo '</tr>';
            }
            echo '</table>';
            echo '</body></html>';
        } else {
            // For unsupported file types
            echo 'Unsupported file format.';
        }
    } else {
        echo 'File not found.';
    }
} else {
    echo 'File parameter is missing.';
}

?>