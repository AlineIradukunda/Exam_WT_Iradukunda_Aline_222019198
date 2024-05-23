<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logout</title>
</head>
<body>

<h2>Do you want to logout?</h2>

<button onclick="logout()">Yes, logout</button>
<button onclick="cancelLogout()">No, cancel</button>

<script>
function logout() {
    // Perform logout operation
    // This could be an AJAX call to a logout script like logout.php
    <?php
session_start();
session_destroy();
header("Location: login.html");
?>

}

function cancelLogout() {
    // Redirect back to the home page or any other desired location
    window.location.href = 'home.php';
}
</script>

</body>
</html>



