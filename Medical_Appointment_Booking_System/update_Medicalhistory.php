<?php
include('database_connection.php');

// Check if history_id is set in the URL
if (isset($_GET['history_id'])) {
    $hid = $_GET['history_id'];

    // Fetch medical history details based on history ID
    $stmt_medical_history = $connection->prepare("SELECT * FROM medicalhistory WHERE history_id = ?");
    $stmt_medical_history->bind_param("i", $hid);
    $stmt_medical_history->execute();
    $result_medical_history = $stmt_medical_history->get_result();

    if ($result_medical_history->num_rows > 0) {
        $row_medical_history = $result_medical_history->fetch_assoc();
        $pid = $row_medical_history['patient_id'];
        $diagnosis = $row_medical_history['diagnosis'];
        $treatment = $row_medical_history['treatment'];
    } else {
        echo "<script>alert('Medical history not found.'); window.location.href = 'your_page.php';</script>";
        exit(); // Exit if history not found
    }
    $stmt_medical_history->close();
} else {
    echo "<script>alert('History ID not provided.'); window.location.href = 'your_page.php';</script>";
    exit(); // Exit if history_id is not set
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve updated values from form
    $pid = $_POST['pid'];
    $dia = $_POST['dia'];
    $tre = $_POST['tre'];

    // Prepare and bind the parameters for update
    $stmt_update = $connection->prepare("UPDATE medicalhistory SET patient_id=?, diagnosis=?, treatment=? WHERE history_id=?");
    $stmt_update->bind_param("issi", $pid, $dia, $tre, $hid);

    // Execute the update
    if ($stmt_update->execute()) {
        echo "<script>alert('Record updated successfully'); window.location.href = 'Medicalhistory.php';</script>";
    } else {
        echo "<script>alert('Error updating record: " . $stmt_update->error . "');</script>";
    }
    $stmt_update->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Medical History</title>
</head>
<body>
    <h1>Update Medical History</h1>
    <form method="post" onsubmit="return confirm('Are you sure you want to update this record?');">
        <label for="pid">Patient ID:</label>
        <input type="number" id="pid" name="pid" value="<?php echo htmlspecialchars($pid); ?>" required><br><br>

        <label for="dia">Diagnosis:</label>
        <input type="text" id="dia" name="dia" value="<?php echo htmlspecialchars($diagnosis); ?>" required><br><br>

        <label for="tre">Treatment:</label>
        <input type="text" id="tre" name="tre" value="<?php echo htmlspecialchars($treatment); ?>" required><br><br>

        <input type="submit" name="update" value="Update">
    </form>
</body>
</html>
