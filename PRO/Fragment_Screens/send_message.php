<?php
// DB credentials (from your snippet)
$localhost = "localhost";
$username = "root";
$password = "Jaga2457";
$database = "protfolio_project";

// Create connection
$conn = new mysqli($localhost, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Gather and sanitize inputs
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Basic validation
    if ($name === '' || $email === '' || $message === '') {
        // change target as you like
        header('Location: contact.html?error=empty');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: contact.html?error=invalid_email');
        exit;
    }

    // Insert into database using prepared statement
    $stmt = $conn->prepare("INSERT INTO `message` (`name`, `email`, `message`) VALUES (?, ?, ?)");
    if ($stmt === false) {
        // prepare failed
        header('Location: contact.html?error=sql_prepare');
        exit;
    }

    $stmt->bind_param("sss", $name, $email, $message);
    $exec_ok = $stmt->execute();
    $stmt->close();

    if (!$exec_ok) {
        // insert failed
        header('Location: contact.html?error=sql_execute');
        exit;
    }

    // Special-case redirect (exact matches)
    if ($email === 'jaga232006@gmail.com' && $name === 'Jagabandhu Prusty' && $message === 'open') {
        header('Location: ../backend/project.html');
        exit;
    }

    // Default success redirect (change to your desired page)

    // your PHP form-processing code goes here

    // instead of header('Location: thankyou.html');
    echo "
<!DOCTYPE html>
<html>
<head>
  <meta charset='UTF-8'>
  <title>Thank You</title>
  <script>
    window.onload = function() {
      alert('Thank you!');
      window.location.href = 'index.html'; // redirect after popup
    }
  </script>
</head>
<body>
</body>
</html>
";


    exit;
} else {
    // Not a POST request
    header('HTTP/1.1 405 Method Not Allowed');
    echo 'Method Not Allowed';
    exit;
}

$conn->close();
?>