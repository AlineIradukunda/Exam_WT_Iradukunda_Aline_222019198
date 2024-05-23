<?php
include('database_connection.php');

// Check if the query parameter is set
if (isset($_GET['query'])) {
    // Sanitize input to prevent SQL injection
    $searchTerm = $connection->real_escape_string($_GET['query']);

    // Queries for different tables
    $queries = [
        'patients' => "SELECT patient_name FROM patients WHERE patient_name LIKE '%$searchTerm%'",
        'doctors' => "SELECT doctor_name FROM doctors WHERE doctor_name LIKE '%$searchTerm%'",
        'appointments' => "SELECT appointment_date FROM appointments WHERE appointment_date LIKE '%$searchTerm%'",
        'medicalhistory' => "SELECT history_id FROM medicalhistory WHERE history_id LIKE '%$searchTerm%'",
        'prescriptions' => "SELECT prescription_id FROM prescriptions WHERE prescription_id LIKE '%$searchTerm%'",
        'clinics' => "SELECT clinic_name FROM clinics WHERE clinic_name LIKE '%$searchTerm%'",
        'consultations' => "SELECT consultation_id FROM consultations WHERE consultation_id LIKE '%$searchTerm%'",
        'test result' => "SELECT result_id FROM test result WHERE result_id LIKE '%$searchTerm%'",
        'insurance' => "SELECT insurance_name FROM insurance WHERE insurance_name LIKE '%$searchTerm%'",
        'payments' => "SELECT payment_id FROM payments WHERE payment_id LIKE '%$searchTerm%'",
    ];

    // Output search results
    echo "<h2><u>Search Results:</u></h2>";

    foreach ($queries as $table => $sql) {
        $result = $connection->query($sql);
        echo "<h3>Table of $table:</h3>";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p>" . htmlspecialchars($row[array_keys($row)[0]]) . "</p>"; // Dynamic field extraction from result
            }
        } else {
            echo "<p>No results found in $table matching the search term: '" . htmlspecialchars($searchTerm) . "'</p>";
        }
    }

    // Close the connection
    $connection->close();
} else {
    echo "<p>No search term was provided.</p>";
}
?>
