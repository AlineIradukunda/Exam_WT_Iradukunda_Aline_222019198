<?php
// Connection details
include('database_connection.php');

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Handling POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieving form data
    $fname  = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $gend = $_POST['gend'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $activation_co = isset($_POST['activation_co']) ? $_POST['activation_co'] : '';  // Ensure activation_co is set

    // Preparing SQL query
    $sql = "INSERT INTO user (fname, lname, username, gend, email, telephone, password, activation_co) 
    VALUES ('$fname', '$lname', '$username', '$gend', '$email', '$telephone', '$password', '$activation_co')";

    // Executing SQL query
    if ($connection->query($sql) === TRUE) {
        // Redirecting to login page on successful registration
        header("Location: login.html");
        exit();
    } else {
        // Displaying error message if query execution fails
        echo "Error: " . $sql . "<br>" . $connection->error;
    }
}

// Closing database connection
$connection->close();
?>
