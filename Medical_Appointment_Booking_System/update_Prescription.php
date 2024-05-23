<?php
// Include database connection
include('database_connection.php');

// Check if prescription_id is set
if(isset($_GET['prescription_id'])) {
    // Fetch prescription details based on prescription ID
    $prescription_id = $_GET['prescription_id'];
    $stmt = $connection->prepare("SELECT * FROM prescriptions WHERE prescription_id = ?");
    $stmt->bind_param("i", $prescription_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $patient_id = $row['patient_id'];
        $doctor_id = $row['doctor_id'];
        $medication = $row['medication'];
    } else {
        echo "<script>alert('Prescription not found.'); window.location.href = 'Prescription.php';</script>";
        exit(); // Exit if prescription not found
    }
    $stmt->close();
} else {
    echo "<script>alert('Prescription ID not provided.'); window.location.href = 'Prescription.php';</script>";
    exit(); // Exit if prescription_id is not set
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
    <title>Update Prescription</title>
</head>
<body>
    <h1><u>Update Prescription</u></h1>
    <!-- Form to update prescription -->
    <form method="post" onsubmit="return confirm('Are you sure you want to update this record?');">
        <input type="hidden" name="prescription_id" value="<?php echo htmlspecialchars($prescription_id); ?>">
        
        <label for="patient_id">Patient ID:</label>
        <input type="text" id="patient_id" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>" required><br><br>

        <label for="doctor_id">Doctor ID:</label>
        <input type="text" id="doctor_id" name="doctor_id" value="<?php echo htmlspecialchars($doctor_id); ?>" required><br><br>

        <label for="medication">Medication:</label>
        <input type="text" id="medication" name="medication" value="<?php echo htmlspecialchars($medication); ?>" required><br><br>

        <input type="submit" name="update_prescription" value="Update">
    </form>

    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_prescription'])) {
        // Prepare and bind the parameters
        $stmt = $connection->prepare("UPDATE prescriptions SET patient_id=?, doctor_id=?, medication=? WHERE prescription_id=?");
        $stmt->bind_param("sssi", $patient_id, $doctor_id, $medication, $prescription_id);

        // Set parameters and execute
        $prescription_id = $_POST['prescription_id'];
        $patient_id = $_POST['patient_id'];
        $doctor_id = $_POST['doctor_id'];
        $medication = $_POST['medication'];

        if ($stmt->execute()) {
            echo "<script>alert('Prescription record has been updated successfully'); window.location.href = 'Prescription.php';</script>";
        } else {
            echo "<script>alert('Error updating prescription: " . htmlspecialchars($stmt->error) . "');</script>";
        }
        $stmt->close();
    }
    $connection->close();
    ?>
</body>
</html>
