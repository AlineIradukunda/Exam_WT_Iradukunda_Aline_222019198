<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Define character encoding -->
  <meta charset="UTF-8">
  <!-- Set viewport for responsive design -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Title of the page -->
  <title>Update Insurance Information</title>
  <!-- Link to external stylesheet -->
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<header>
  <!-- Your header content here -->
</header>

<section>
  <h1><u>Update Insurance Information</u></h1>
  <?php
  // Include database connection
  include('database_connection.php');

  // Check connection
  $connection = new mysqli($host, $user, $pass, $database);
  if ($connection->connect_error) {
      die("Connection failed: " . $connection->connect_error);
  }

  // Check if insurance_id is set in the URL
  if (isset($_GET['insurance_id'])) {
      $insurance_id = $_GET['insurance_id'];

      // Fetch insurance details based on insurance ID
      $stmt_insurance = $connection->prepare("SELECT * FROM insurance WHERE insurance_id = ?");
      $stmt_insurance->bind_param("i", $insurance_id);
      $stmt_insurance->execute();
      $result_insurance = $stmt_insurance->get_result();

      if ($result_insurance->num_rows > 0) {
          $row_insurance = $result_insurance->fetch_assoc();
          $patient_id = $row_insurance['patient_id'];
          $insurance_provider = $row_insurance['insurance_provider'];
          $policy_number = $row_insurance['policy_number'];
      } else {
          echo "<script>alert('Insurance record not found.'); window.location.href = 'insurance.php';</script>";
          exit(); // Exit if insurance record not found
      }
      $stmt_insurance->close();
  } else {
      echo "<script>alert('Insurance ID not provided.'); window.location.href = 'insurance.php';</script>";
      exit(); // Exit if insurance_id is not set
  }
  ?>

  <!-- Update Insurance Information Form -->
  <form method="post" onsubmit="return confirm('Are you sure you want to update this record?');">
    <label for="patient_id">Patient ID:</label>
    <input type="number" id="patient_id" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>" required><br><br>

    <label for="insurance_provider">Insurance Provider:</label>
    <input type="text" id="insurance_provider" name="insurance_provider" value="<?php echo htmlspecialchars($insurance_provider); ?>" required><br><br>

    <label for="policy_number">Policy Number:</label>
    <input type="text" id="policy_number" name="policy_number" value="<?php echo htmlspecialchars($policy_number); ?>" required><br><br>

    <input type="submit" name="update_insurance" value="Update">
  </form>

  <?php
  // Check if the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_insurance'])) {
      // Retrieve updated values from form
      $patient_id = $_POST['patient_id'];
      $insurance_provider = $_POST['insurance_provider'];
      $policy_number = $_POST['policy_number'];

      // Prepare and bind the parameters for update
      $stmt_update = $connection->prepare("UPDATE insurance SET patient_id=?, insurance_provider=?, policy_number=? WHERE insurance_id=?");
      $stmt_update->bind_param("issi", $patient_id, $insurance_provider, $policy_number, $insurance_id);

      // Execute the update
      if ($stmt_update->execute()) {
          echo "<script>alert('Record updated successfully'); window.location.href = 'insurance.php';</script>";
      } else {
          echo "<script>alert('Error updating record: " . $stmt_update->error . "');</script>";
      }
      $stmt_update->close();
  }

  // Close the connection
  $connection->close();
  ?>
</section>

<footer>
  <!-- Your footer content here -->
</footer>
</body>
</html>
