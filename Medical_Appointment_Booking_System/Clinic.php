<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Linking to external stylesheet -->
  <link rel="stylesheet" type="text/css" href="style.css" title="style 1" media="screen, tv, projection, handheld, print"/>
  <!-- Defining character encoding -->
  <meta charset="utf-8">
  <!-- Setting viewport for responsive design -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> clinics </title>
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
    }


    {
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
    }
  </style>
</head>
<body>
<header><center>
    <h1>Clinics Page</h1></center>
  </header>
  <form class="d-flex" role="search" action="search.php">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="query">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  <ul>
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
  <h1>Clinics form</h1>
  <!-- Form to add a new employee -->
  <form method="post">
    <label for="cid">clinic_id:</label>
    <input type="number" id="cid" name="cid"><br><br>

    <label for="cn">clinic_name:</label>
    <input type="text" id="cn" name="cn" required><br><br>

    <label for="lct">location:</label>
    <input type="text" id="lct" name="lct" required><br><br>

    <input type="submit" name="add" value="Insert"><br><br>
    <a class="button" href="./home.html">Go Back to Home</a></li>
  </form>

  <?php
  include('database_connection.php');

  // Check if the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Prepare and bind the parameters
      $stmt = $connection->prepare("INSERT INTO clinics(clinic_id, clinic_name, location) VALUES (?, ?, ?)");
      $stmt->bind_param("iss", $cid, $cn, $lct);

      // Set parameters and execute
      $cid = $_POST['cid'];
      $cn = $_POST['cn'];
      $lct = $_POST['lct'];
      
      if ($stmt->execute() == TRUE) {
          echo "New record has been added successfully";
      } else {
          echo "Error: " . $stmt->error;
      }
      $stmt->close();
  }
  $connection->close();
  ?>

  <h2>Table of clinics</h2>
  <!-- Displaying employee records -->
  <table border="3">
    <tr>
      <th>clinic-id</th>
      <th>clinic_name</th>
      <th>location</th>
      <th>Delete</th>
      <th>Update</th>
    </tr>
    <?php
    include('database_connection.php');

    // Prepare SQL query to retrieve all employees
    $sql = "SELECT * FROM clinics";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cid = $row['clinic_id'];
            echo "<tr>
                <td>" . $row['clinic_id'] . "</td>
                <td>" . $row['clinic_name'] . "</td>
                <td>" . $row['location'] . "</td>
                
                <td><a style='padding:4px' href='delete_Clinic.php?clinic_id=$cid'>Delete</a></td> 
                <td><a style='padding:4px' href='update_Clinic.php?clinic_id=$cid'>Update</a></td> 
            </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No data found</td></tr>";
    }
    // Close the database connection
    $connection->close();
    ?>
  </table>
</section>

<footer>
  <center> 
    <b><h2>UR CBE BIT &copy; <?php echo date("Y"); ?> &reg;, Designer by: @ALINE IRADUKUNDA</h2></b>
  </center>
</footer>
</body>
</html>
