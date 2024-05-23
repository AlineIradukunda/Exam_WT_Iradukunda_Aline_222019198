<?php
// Connection details
include('database_connection.php');

// Check connection
$connection = new mysqli($host, $user, $pass, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if appointment_id is set
if (isset($_GET['appointment_id'])) {
    $aid = $_GET['appointment_id'];
    
    // Fetch appointment details based on appointment ID
    $stmt_appointment = $connection->prepare("SELECT * FROM appointments WHERE appointment_id = ?");
    $stmt_appointment->bind_param("i", $aid);
    $stmt_appointment->execute();
    $result_appointment = $stmt_appointment->get_result();
    
    if ($result_appointment->num_rows > 0) {
        $row_appointment = $result_appointment->fetch_assoc();
        $pid = $row_appointment['patient_id'];
        $did = $row_appointment['doctor_id'];
        $ad = $row_appointment['appointment_date'];
        $ar = $row_appointment['appointment_reason'];
    } else {
        echo "<script>alert('Appointment not found.'); window.location.href = 'appointment.php';</script>";
        exit(); // Exit if appointment not found
    }
    $stmt_appointment->close();
} else {
    echo "<script>alert('Appointment ID not provided.'); window.location.href = 'appointment.php';</script>";
    exit(); // Exit if appointment_id is not set
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Linking to external stylesheet -->
    <link rel="stylesheet" type="text/css" href="style.css" title="style 1" media="screen, tv, projection, handheld, print"/>
    <!-- Defining character encoding -->
    <meta charset="utf-8">
    <!-- Setting viewport for responsive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Appointment</title>
</head>
<body>
    <h1><u>Update Appointment</u></h1>
    <!-- Form to update appointment -->
    <form method="post" onsubmit="return confirm('Are you sure you want to update this record?');">
        <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($aid); ?>">

        <label for="pid">Patient ID:</label>
        <input type="number" id="pid" name="pid" value="<?php echo htmlspecialchars($pid); ?>" required><br><br>

        <label for="did">Doctor ID:</label>
        <input type="number" id="did" name="did" value="<?php echo htmlspecialchars($did); ?>" required><br><br>

        <label for="ad">Appointment Date:</label>
        <input type="date" id="ad" name="ad" value="<?php echo htmlspecialchars($ad); ?>" required><br><br>

        <label for="ar">Appointment Reason:</label>
        <input type="text" id="ar" name="ar" value="<?php echo htmlspecialchars($ar); ?>" required><br><br>

        <input type="submit" name="up" value="Update">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['up'])) {
        // Prepare and bind the parameters
        $stmt_update_appointment = $connection->prepare("UPDATE appointments SET patient_id=?, doctor_id=?, appointment_date=?, appointment_reason=? WHERE appointment_id=?");
        $stmt_update_appointment->bind_param("iisss", $patient_id, $doctor_id, $appointment_date, $appointment_reason, $aid);

        // Set parameters and execute
        $appointment_id = $_POST['appointment_id'];
        $patient_id = $_POST['pid'];
        $doctor_id = $_POST['did'];
        $appointment_date = $_POST['ad'];
        $appointment_reason = $_POST['ar'];
        
        if ($stmt_update_appointment->execute()) {
            echo "<script>alert('Appointment has been updated successfully.'); window.location.href = 'appointment.php';</script>";
        } else {
            echo "<script>alert('Error updating appointment: " . htmlspecialchars($stmt_update_appointment->error) . "');</script>";
        }
        $stmt_update_appointment->close();
        $connection->close();
    }
    ?>
</body>
</html>
