<?php
// Include database connection
include('database_connection.php');

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if payment_id is set
if (isset($_GET['payment_id'])) {
    // Fetch payment details based on payment ID
    $payment_id = $_GET['payment_id'];
    $stmt = $connection->prepare("SELECT * FROM payments WHERE payment_id = ?");
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $patient_id = $row['patient_id'];
        $amount_paid = $row['amount_paid'];
        $payment_date = $row['payment_date'];
    } else {
        echo "<script>alert('Payment not found.'); window.location.href = 'Payment.php';</script>";
        exit(); // Exit if payment not found
    }
    $stmt->close();
} else {
    echo "<script>alert('Payment ID not provided.'); window.location.href = 'Payment.php';</script>";
    exit(); // Exit if payment_id is not set
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
    <title>Update Payment</title>
</head>
<body>
    <h1><u>Update Payment</u></h1>
    <!-- Form to update payment -->
    <form method="post" onsubmit="return confirm('Are you sure you want to update this record?');">
        <input type="hidden" name="payment_id" value="<?php echo htmlspecialchars($payment_id); ?>">
        
        <label for="patient_id">Patient ID:</label>
        <input type="number" id="patient_id" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>" required><br><br>

        <label for="amount_paid">Amount Paid:</label>
        <input type="text" id="amount_paid" name="amount_paid" value="<?php echo htmlspecialchars($amount_paid); ?>" required><br><br>

        <label for="payment_date">Payment Date:</label>
        <input type="text" id="payment_date" name="payment_date" value="<?php echo htmlspecialchars($payment_date); ?>" required><br><br>

        <input type="submit" name="update_payment" value="Update">
    </form>

    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_payment'])) {
        // Prepare and bind the parameters
        $stmt_update = $connection->prepare("UPDATE payments SET patient_id=?, amount_paid=?, payment_date=? WHERE payment_id=?");
        $stmt_update->bind_param("idss", $patient_id, $amount_paid, $payment_date, $payment_id);

        // Set parameters and execute
        $payment_id = $_POST['payment_id'];
        $patient_id = $_POST['patient_id'];
        $amount_paid = $_POST['amount_paid'];
        $payment_date = $_POST['payment_date'];

        if ($stmt_update->execute()) {
            echo "<script>alert('Payment record has been updated successfully.'); window.location.href = 'Payment.php';</script>";
        } else {
            echo "Error updating payment record: " . $stmt_update->error;
        }
        $stmt_update->close();
    }
    $connection->close();
    ?>
</body>
</html>
