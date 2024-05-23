<?php
// Connection details
include('database_connection.php');

// Check connection
$connection = new mysqli($host, $user, $pass, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if clinic_id is set
if (isset($_REQUEST['clinic_id'])) {
    $cid = $_REQUEST['clinic_id'];
    
    // Fetch clinic details based on clinic ID
    $stmt_clinic = $connection->prepare("SELECT * FROM clinics WHERE clinic_id = ?");
    $stmt_clinic->bind_param("i", $cid);
    $stmt_clinic->execute();
    $result_clinic = $stmt_clinic->get_result();
    
    if ($result_clinic->num_rows > 0) {
        $row_clinic = $result_clinic->fetch_assoc();
        $cn = $row_clinic['clinic_name'];
        $lct = $row_clinic['location'];
    } else {
        echo "Clinic not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Clinic</title>
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this clinic?');
        }
    </script>
</head>
<body>
    <form method="POST" id="updateForm" onsubmit="return confirmUpdate();">
        <label for="cn">Clinic Name:</label>
        <input type="text" name="cn" value="<?php echo isset($cn) ? htmlspecialchars($cn) : ''; ?>" required>
        <br><br>

        <label for="lct">Location:</label>
        <input type="text" name="lct" value="<?php echo isset($lct) ? htmlspecialchars($lct) : ''; ?>" required>
        <br><br>

        <input type="submit" name="up" value="Update">
    </form>

    <?php
    if (isset($_POST['up'])) {
        // Retrieve updated values from form
        $clinic_name = $_POST['cn'];
        $location = $_POST['lct'];
        
        // Update the clinic in the database
        $stmt_update_clinic = $connection->prepare("UPDATE clinics SET clinic_name=?, location=? WHERE clinic_id=?");
        $stmt_update_clinic->bind_param("ssi", $clinic_name, $location, $cid);
        $stmt_update_clinic->execute();
        
       // Display popup message
        echo "<script>alert('Update successful.'); window.location.href = 'Clinic.php';</script>";
        
        // Redirect to Clinic.php (or any appropriate page)
        // header('Location: patient.php');
        // exit(); // Ensure that no other content is sent after the header redirection
    }

    // Close the connection
    $connection->close();
    ?>
</body>
</html>
