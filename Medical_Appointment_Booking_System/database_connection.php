<?php
// Connection details
$host = "localhost";
$user = "Aline";
$pass = "222019198";
$database = "medical_appointment_booking_system";

// Creating connection
$connection = new mysqli($host, $user, $pass, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
