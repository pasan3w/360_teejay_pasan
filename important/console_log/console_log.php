<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the log message from the POST request
    $logMessage = $_POST["logMessage"];

    // Set the path to your log file
    if ((!empty($_POST['type'])) && ($_POST['type'] == 'resend')) {
        $logFilePath = "resend/" . date("Y-m-d H:i:s") . "log.txt";
    } else {
        $logFilePath = "aws/" . date("Y-m-d H:i:s") . "log.txt";
    }

    // Create or append to the log file with error handling
    if (file_exists($logFilePath) || touch($logFilePath)) {
        if (file_put_contents($logFilePath, date("Y-m-d H:i:s") . " - " . $logMessage . "\n", FILE_APPEND)) {
            echo "Log message saved successfully.";
        } else {
            http_response_code(500);
            echo "Error writing to the log file.";
        }
    } else {
        http_response_code(500);
        echo "Error creating the log file.";
    }
} else {
    // Handle invalid requests
    http_response_code(400);
    echo "Invalid request.";
}
?>