<?php
// Include the database connection file
require("Common/DBCon.php");

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Sanitize user input to prevent SQL injection
    $escaped_username = mysqli_real_escape_string($conn, $username);

    // Prepare the SQL statement with a parameterized query
    $query = "SELECT Password FROM login WHERE UserName=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $escaped_username);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Get the result from the query
    $result = mysqli_stmt_get_result($stmt);

    // Check if the query was successful and fetch the user data
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $hashed_password = $user['Password'];

        // Verify the password using password_verify()
        if (password_verify($password, $hashed_password)) {
            // Successful login, redirect the user to a dashboard or home page
            header("Location: Home.php");
            exit;
        } else {
            // Invalid credentials, show an error message or redirect back to the login page
            header("Location: login.php?error=invalid_credentials");
            exit;
        }
    } else {
        // User not found, show an error message or redirect back to the login page
        header("Location: login.php?error=invalid_credentials");
        exit;
    }

    // Close the statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
