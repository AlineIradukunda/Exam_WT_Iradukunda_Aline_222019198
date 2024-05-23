<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Linking to external stylesheet -->
  <link rel="stylesheet" type="text/css" href="style.css" title="style 1" media="screen, tv, projection, handheld, print"/>
  <!-- Defining character encoding -->
  <meta charset="utf-8">
  <!-- Setting viewport for responsive design -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> insurance </title>
  <style>
    body
    /* Table style */
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid black;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: pink;
    } {
      margin: 0;
      padding: 0;
      font-family: 'Times New Roman', serif;
      background: url('./Images/yy.JPG') no-repeat center center/cover;
      height: 100vh;
    }
    a {
      padding: 10px;
      color: white;
      text-decoration: none;
      margin-right: 15px;
    }
    a:visited {
      color: purple;
    }
    a:link {
      color: brown;
    }
    a:hover {
      background-color: white;
    }
    a:active {
      background-color: green;
    }
    button.btn {
      margin-left: 15px;
      margin-top: 4px;
    }
    input.form-control {
      margin-left: 15px;
      padding: 8px;
    }
    section {
      padding: 71px;
      border-bottom: 1px solid #ddd;
    }
    footer {
      text-align: center;
      padding: 15px;
      background-color: darkslategrey;
    }
  header {
      background-color: darkcyan;
      padding: 10px;
      text-align: center;
    }
    ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
      display: flex;
      align-items: center;
    }
    li {
      margin-right: 10px;
    }
    li img {
      vertical-align: middle;
    }
    .dropdown {
      position: relative;
      display: inline-block;
    }
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
    }
    .dropdown:hover .dropdown-content {
      display: block;
    }
    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }
    .dropdown-content a:hover {
      background-color: #f1f1f1;
    }
    .button{
      padding: 6px;
  background-color: orange; /* Example style, adjust as needed */
  color: brown;
  border: none;
  </style>
</head>
<body>
<header><center>
    <h1>Insurance Page</h1></center>
  </header>  <form class="d-flex" role="search" action="search.php">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="query">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form> <ul>
    <li><img src="./Images/app.jpg" width="90" height="60" alt="Logo"></li>
    <li><a href="./home.html">HOME</a></li>
    <li><a href="./about.html">ABOUT</a></li>
    <li><a href="./contact.html">CONTACT</a></li>
    <li class="dropdown">
      <a href="#" style="padding: 20px; color: white; background-color: skyblue; text-decoration: none;">MENU</a>
      <div class="dropdown-content">
        <a href="./Patients.php">PATIENTS</a>
        <a href="./Doctors.php">DOCTORS</a>
        <a href="./Appointment.php">APPOINTMENT</a>
        <a href="./Medicalhistory.php">MEDICAL HISTORY</a>
        <a href="./Prescription.php">PRESCRIPTION</a>
        <a href="./Clinic.php">CLINIC</a>
        <a href="./Consultation.php">CONSULTATION</a>
        <a href="./TestResult.php">TEST RESULT</a>
        <a href="./Insurance.php">INSURANCE</a>
        <a href="./Payment.php">PAYMENT</a>
      </div>
    </li>
    <li class="dropdown">
      <a href="#" style="padding: 20px; color: white; background-color: skyblue; text-decoration: none;">Settings</a>
      <div class="dropdown-content">
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
        <a href="logout.php">Logout</a>
      </div>
    </li>
  </ul>


<section>
  <h1>Insurance Information</h1>
  <!-- Form to add a new insurance -->
  <form method="post">
    <label for="insurance_id">Insurance ID:</label>
    <input type="number" id="insurance_id" name="insurance_id" readonly><br><br>

    <label for="patient_id">Patient ID:</label>
    <input type="number" id="patient_id" name="patient_id" required><br><br>

    <label for="insurance_provider">Insurance Provider:</label>
    <input type="text" id="insurance_provider" name="insurance_provider" required><br><br>

    <label for="policy_number">Policy Number:</label>
    <input type="text" id="policy_number" name="policy_number" required><br><br>

    <input type="submit" name="add_insurance" value="Insert"><br><br>
    <a class="button" href="./home.html">Go Back to Home</a></li>
  </form>

  <?php
  include('database_connection.php');

  // Check if the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_insurance'])) {
      // Prepare and bind the parameters
      $stmt = $connection->prepare("INSERT INTO Insurance(patient_id, insurance_provider, policy_number) VALUES (?, ?, ?)");
      $stmt->bind_param("iss", $patient_id, $insurance_provider, $policy_number);

      // Set parameters and execute
      $patient_id = $_POST['patient_id'];
      $insurance_provider = $_POST['insurance_provider'];
      $policy_number = $_POST['policy_number'];
      
      if ($stmt->execute()) {
          echo "New insurance record has been added successfully";
      } else {
          echo "Error: " . $stmt->error;
      }
      $stmt->close();
  }
  $connection->close();
  ?>

  <h2>Table of Insurance Information</h2>
  <!-- Displaying insurance records -->
  <table border="1">
    <tr>
      <th>Insurance ID</th>
      <th>Patient ID</th>
      <th>Insurance Provider</th>
      <th>Policy Number</th>
      <th>Delete</th>
      <th>Update</th>
    </tr>
    <?php
    include('database_connection.php');

    // Prepare SQL query to retrieve all insurance
    $sql = "SELECT * FROM Insurance";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $insurance_id = $row['insurance_id'];
            echo "<tr>
                <td>" . $row['insurance_id'] . "</td>
                <td>" . $row['patient_id'] . "</td>
                <td>" . $row['insurance_provider'] . "</td>
                <td>" . $row['policy_number'] . "</td>
                <td><a href='delete_insurance.php?insurance_id=$insurance_id'>Delete</a></td> 
                <td><a href='update_insurance.php?insurance_id=$insurance_id'>Update</a></td> 
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No insurance data found</td></tr>";
    }
    // Close the database connection
    $connection->close();
    ?>
  </table>
</section>

<footer>
  <center> 
    <b><h2>UR CBE BIT &copy; <?php echo date("Y"); ?> &reg;, Designed by: @ALINE IRADUKUNDA</h2></b>
  </center>
</footer>
</body>
</html>
