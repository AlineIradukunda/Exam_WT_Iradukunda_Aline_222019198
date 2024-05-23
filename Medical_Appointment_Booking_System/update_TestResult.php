<?php
// Check if result_id is set
if(isset($_GET['result_id'])) {
    // Include database connection
    include('database_connection.php');
    
    // Fetch test result details based on result ID
    $result_id = $_GET['result_id'];
    $stmt = $connection->prepare("SELECT * FROM test_results WHERE result_id = ?");
    $stmt->bind_param("i", $result_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $result_id = $row['result_id'];
        $appointment_id = $row['appointment_id'];
        $test_id = $row['test_id'];
        $result_date = $row['result_date'];
    } else {
        echo "<script>alert('Test result not found.'); window.location.href = 'TestResult.php';</script>";
        exit(); // Exit if test result not found
    }
    $stmt->close();
} else {
    echo "<script>alert('Result ID not provided.'); window.location.href = 'TestResult.php';</script>";
    exit(); // Exit if result_id is not set
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
  <title>Update Test Results</title>
</head>
<body>
  <h1><u>Update Test Results</u></h1>
  <!-- Form to update test results -->
  <form method="post" onsubmit="return confirm('Are you sure you want to update this record?');">
    <input type="hidden" name="result_id" value="<?php echo htmlspecialchars($result_id); ?>">
   <label for="result_date">Result ID:</label>
    <input type="number" id="result_id" name="result_id" value="<?php echo htmlspecialchars($result_id); ?>" required><br><br>


    <label for="appointment_id">Appointment ID:</label>
    <input type="number" id="appointment_id" name="appointment_id" value="<?php echo htmlspecialchars($appointment_id); ?>" required><br><br>

    <label for="test_id">Test ID:</label>
    <input type="number" id="test_id" name="test_id" value="<?php echo htmlspecialchars($test_id); ?>" required><br><br>

    <label for="result_date">Result Date:</label>
    <input type="date" id="result_date" name="result_date" value="<?php echo htmlspecialchars($result_date); ?>" required><br><br>

    <input type="submit" name="update_result" value="Update">
  </form>

  <?php
  // Check if the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_result'])) {
      // Include database connection
      include('database_connection.php');
      
      // Prepare and bind the parameters
      $stmt = $connection->prepare("UPDATE test_results SET appointment_id=?, test_id=?, result_date=? WHERE result_id=?");
      $stmt->bind_param("iisi", $appointment_id, $test_id, $result_date, $result_id);

      // Set parameters and execute
      $result_id = $_POST['result_id'];
      $appointment_id = $_POST['appointment_id'];
      $test_id = $_POST['test_id'];
      $result_date = $_POST['result_date'];
      
      if ($stmt->execute()) {
          echo "<script>alert('Test result has been updated successfully'); window.location.href = 'TestResult.php';</script>";
      } else {
          echo "<script>alert('Error updating test result: " . htmlspecialchars($stmt->error) . "');</script>";
      }
      $stmt->close();
      $connection->close();
  }
  ?>
</body>
</html>
