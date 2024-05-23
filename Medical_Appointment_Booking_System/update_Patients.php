<?php
// Include database connection
include('database_connection.php');

// Check connection
$connection = new mysqli($host, $user, $pass, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if patient_id is set
if (isset($_GET['patient_id'])) {
    $pid = $_GET['patient_id'];
    
    // Fetch patient details based on patient ID
    $stmt_patient = $connection->prepare("SELECT * FROM patients WHERE patient_id = ?");
    $stmt_patient->bind_param("i", $pid);
    $stmt_patient->execute();
    $result_patient = $stmt_patient->get_result();
    
    if ($result_patient->num_rows > 0) {
        $row_patient = $result_patient->fetch_assoc();
        $pname = $row_patient['patient_name'];
        $age = $row_patient['age'];
        $gender = $row_patient['gender'];
        $adrss = $row_patient['address'];
        $tlphn = $row_patient['phone_number'];
        $eml = $row_patient['email'];
    } else {
        echo "<script>alert('Patient not found.'); window.location.href = 'Patients.php';</script>";
        exit();
    }
    $stmt_patient->close();
} else {
    echo "<script>alert('Patient ID not provided.'); window.location.href = 'Patients.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient Information</title>
</head>
<body>
    <h1>Update Patient Information</h1>
    <form method="post" onsubmit="return confirm('Are you sure you want to update this record?');">
        <label for="pname">Patient Name:</label>
        <input type="text" id="pname" name="pname" value="<?php echo htmlspecialchars($pname); ?>" required><br><br>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>" required><br><br>

        <label for="gender">Gender:</label>
        <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($gender); ?>" required><br><br>

        <label for="adrss">Address:</label>
        <input type="text" id="adrss" name="adrss" value="<?php echo htmlspecialchars($adrss); ?>" required><br><br>

        <label for="tlphn">Phone Number:</label>
        <input type="text" id="tlphn" name="tlphn" value="<?php echo htmlspecialchars($tlphn); ?>" required><br><br>

        <label for="eml">Email:</label>
        <input type="email" id="eml" name="eml" value="<?php echo htmlspecialchars($eml); ?>" required><br><br>

        <input type="submit" name="up" value="Update">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['up'])) {
        // Retrieve updated values from form
        $patient_name = $_POST['pname'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $address = $_POST['adrss'];
        $phone_number = $_POST['tlphn'];
        $email = $_POST['eml'];

        // Prepare and bind the parameters for update
        $stmt_update_patient = $connection->prepare("UPDATE patients SET patient_name=?, age=?, gender=?, address=?, phone_number=?, email=? WHERE patient_id=?");
        $stmt_update_patient->bind_param("sissssi", $patient_name, $age, $gender, $address, $phone_number, $email, $pid);

        // Execute the update
        if ($stmt_update_patient->execute()) {
            echo "<script>alert('Update successful.'); window.location.href = 'Patients.php';</script>";
        } else {
            echo "<script>alert('Error updating record: " . $stmt_update_patient->error . "');</script>";
        }
        $stmt_update_patient->close();
    }

    // Close the database connection
    $connection->close();
    ?>
</body>
</html>
