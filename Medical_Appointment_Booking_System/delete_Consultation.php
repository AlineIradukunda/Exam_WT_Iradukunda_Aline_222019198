<?php
include('database_connection.php');

// Function to show delete confirmation modal
function showDeleteConfirmation($consultation_id) {
    echo <<<HTML
    <div id="confirmModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center;">
        <div style="background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 15px rgba(0,0,0,0.2);">
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete this record?</p>
            <button onclick="confirmDeletion($consultation_id)">Confirm</button>
            <button onclick="returnToConsultations()">Back</button>
        </div>
    </div>
    <script>
    function confirmDeletion(consultation_id) {
        window.location.href = '?consultation_id=' + consultation_id + '&confirm=yes';
    }
    function returnToConsultations() {
        window.location.href = 'Consultation.php';
    }
    </script>
HTML;
}

// Check if consultation_id is set
if(isset($_REQUEST['consultation_id'])) {
    $consultation_id = $_REQUEST['consultation_id'];
    
    // Check for confirmation response
    if(isset($_REQUEST['confirm']) && $_REQUEST['confirm'] == 'yes') {
        // Prepare and execute the DELETE statement
        $stmt = $connection->prepare("DELETE FROM consultation WHERE consultation_id=?");
        $stmt->bind_param("i", $consultation_id);
        if($stmt->execute()) {
            echo "<script>alert('Record deleted successfully.'); window.location.href = 'Consultation.php';</script>";
        } else {
            echo "<script>alert('Error deleting data: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        // Show confirmation dialog
        showDeleteConfirmation($consultation_id);
    }
} else {
    echo "<script>alert('Consultation ID is not set.'); window.location.href = 'Consultation.php';</script>";
}

$connection->close();
?>
