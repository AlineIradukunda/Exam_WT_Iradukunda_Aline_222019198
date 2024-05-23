<?php
// Connection details
include('database_connection.php');

// Check connection
$connection = new mysqli($host, $user, $pass, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if doctor_id is set
if (isset($_REQUEST['doctor_id'])) {
    $did = $_REQUEST['doctor_id'];
    
    // Fetch doctor details based on doctor ID
    $stmt_doctors = $connection->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
    $stmt_doctors->bind_param("i", $did);
    $stmt_doctors->execute();
    $result_doctors = $stmt_doctors->get_result();
    
    if ($result_doctors->num_rows > 0) {
        $row_doctors = $result_doctors->fetch_assoc();
        $dn = $row_doctors['doctor_name'];
        $sp = $row_doctors['specialty'];
        $tlphn = $row_doctors['phone_number'];
    } else {
        echo "<script>alert('Doctor not found.'); window.location.href = 'Doctors.php';</script>";
        exit(); // Exit if doctor not found
    }
    $stmt_doctors->close();
} else {
    echo "<script>alert('Doctor ID not provided.'); window.location.href = 'Doctors.php';</script>";
    exit(); // Exit if doctor_id is not set
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Doctor</title>
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this record?');
        }
    </script>
</head>
<body>
    <h1><u>Update Doctor</u></h1>
    <form method="post" onsubmit="return confirmUpdate();">
        <label for="did">Doctor ID:</label>
        <input type="number" id="did" name="did" value="<?php echo htmlspecialchars($did); ?>" readonly><br><br>

        <label for="dn">Doctor Name:</label>
        <input type="text" id="dn" name="dn" value="<?php echo htmlspecialchars($dn); ?>" required><br><br>

        <label for="sp">Specialty:</label>
        <input type="text" id="sp" name="sp" value="<?php echo htmlspecialchars($sp); ?>" required><br><br>

        <label for="tlphn">Phone Number:</label>
        <input type="text" id="tlphn" name="tlphn" value="<?php echo htmlspecialchars($tlphn); ?>" required><br><br>

        <input type="submit" name="up" value="Update">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['up'])) {
        // Retrieve updated values from form
        $dn = $_POST['dn'];
        $sp = $_POST['sp'];
        $tlphn = $_POST['tlphn'];
        
        // Update the doctor in the database
        $stmt_update_doctors = $connection->prepare("UPDATE doctors SET doctor_name=?, specialty=?, phone_number=? WHERE doctor_id=?");
        $stmt_update_doctors->bind_param("sssi", $dn, $sp, $tlphn, $did);
        
        if ($stmt_update_doctors->execute()) {
            echo "<script>alert('Update successful.'); window.location.href = 'Doctors.php';</script>";
        } else {
            echo "<script>alert('Error updating doctor record: " . $stmt_update_doctors->error . "');</script>";
        }
        $stmt_update_doctors->close();
    }

    // Close the connection
    $connection->close();
    ?>
</body>
</html>
