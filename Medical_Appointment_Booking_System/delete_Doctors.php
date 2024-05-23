<?php
include('database_connection.php');

// Function to show delete confirmation modal
function showDeleteConfirmation($doctor_id) {
    echo <<<HTML
    <div id="confirmModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center;">
        <div style="background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 15px rgba(0,0,0,0.2);">
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete this record?</p>
            <button onclick="confirmDeletion($doctor_id)">Confirm</button>
            <button onclick="returnToInsurance()">Back</button>
        </div>
    </div>
    <script>
    function confirmDeletion(doctor_id) {
        window.location.href = '?doctor_id=' + doctor_id + '&confirm=yes';
    }
    function returnToInsurance() {
        window.location.href = 'Doctors.php';
    }
    </script>
HTML;
}

// Check if insurance_id is set
if(isset($_REQUEST['doctor_id'])) {
    $doctor_id = $_REQUEST['doctor_id'];
    
    // Check for confirmation response
    if(isset($_REQUEST['confirm']) && $_REQUEST['confirm'] == 'yes') {
        // Prepare and execute the DELETE statement
        $stmt = $connection->prepare("DELETE FROM doctors WHERE doctor_id=?");
        $stmt->bind_param("i", $doctor_id);
        if($stmt->execute()) {
            echo "<script>alert('Record deleted successfully.'); window.location.href = 'Doctors.php';</script>";
        } else {
            echo "<script>alert('Error deleting data: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        // Show confirmation dialog
        showDeleteConfirmation($doctor_id);
    }
} else {
    echo "<script>alert('Doctors ID is not set.'); window.location.href = 'Doctors.php';</script>";
}

$connection->close();
?>
