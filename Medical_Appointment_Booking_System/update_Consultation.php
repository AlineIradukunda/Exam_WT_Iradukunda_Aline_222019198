<?php
// Connection details
include('database_connection.php');

// Check connection
$connection = new mysqli($host, $user, $pass, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if consultation_id is set
if (isset($_GET['consultation_id'])) {
    $consultation_id = $_GET['consultation_id'];
    
    // Fetch consultation details based on consultation ID
    $stmt = $connection->prepare("SELECT * FROM Consultation WHERE consultation_id = ?");
    $stmt->bind_param("i", $consultation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $appointment_id = $row['appointment_id'];
        $doctor_id = $row['doctor_id'];
        $consultation_date = $row['consultation_date'];
        $consultation_time = $row['consultation_time'];
        $consultation_notes = $row['consultation_notes'];
    } else {
        echo "<script>alert('Consultation not found.'); window.location.href = 'Consultation.php';</script>";
        exit(); // Exit if consultation not found
    }
    $stmt->close();
} else {
    echo "<script>alert('Consultation ID not provided.'); window.location.href = 'Consultation.php';</script>";
    exit(); // Exit if consultation_id is not set
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Consultation</title>
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this consultation?');
        }
    </script>
</head>
<body>
    <h1><u>Update Consultation</u></h1>
    <!-- Form to update consultation -->
    <form method="post" onsubmit="return confirmUpdate();">
        <label for="appointment_id">Appointment ID:</label>
        <input type="number" id="appointment_id" name="appointment_id" value="<?php echo htmlspecialchars($appointment_id); ?>" required><br><br>

        <label for="doctor_id">Doctor ID:</label>
        <input type="number" id="doctor_id" name="doctor_id" value="<?php echo htmlspecialchars($doctor_id); ?>" required><br><br>

        <label for="consultation_date">Consultation Date:</label>
        <input type="date" id="consultation_date" name="consultation_date" value="<?php echo htmlspecialchars($consultation_date); ?>" required><br><br>

        <label for="consultation_time">Consultation Time:</label>
        <input type="time" id="consultation_time" name="consultation_time" value="<?php echo htmlspecialchars($consultation_time); ?>" required><br><br>

        <label for="consultation_notes">Consultation Notes:</label>
        <input type="text" id="consultation_notes" name="consultation_notes" value="<?php echo htmlspecialchars($consultation_notes); ?>" required><br><br>

        <input type="submit" name="update_consultation" value="Update">
    </form>

    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_consultation'])) {
        // Prepare and bind the parameters for update
        $stmt_update = $connection->prepare("UPDATE Consultation SET appointment_id=?, doctor_id=?, consultation_date=?, consultation_time=?, consultation_notes=? WHERE consultation_id=?");
        $stmt_update->bind_param("iisssi", $appointment_id, $doctor_id, $consultation_date, $consultation_time, $consultation_notes, $consultation_id);

        // Set parameters and execute
        $appointment_id = $_POST['appointment_id'];
        $doctor_id = $_POST['doctor_id'];
        $consultation_date = $_POST['consultation_date'];
        $consultation_time = $_POST['consultation_time'];
        $consultation_notes = $_POST['consultation_notes'];
        
        if ($stmt_update->execute()) {
            echo "<script>alert('Consultation record has been updated successfully'); window.location.href = 'Consultation.php';</script>";
        } else {
            echo "<script>alert('Error updating consultation record: " . $stmt_update->error . "');</script>";
        }
        $stmt_update->close();
    }

    // Close the connection
    $connection->close();
    ?>
</body>
</html>
