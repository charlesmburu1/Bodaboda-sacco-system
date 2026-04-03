<?php
// $conn = new mysqli("localhost", "root", "", "your_database");
    require "includes/config.php";

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Get data
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Insert into DB
$stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);
$stmt->execute();

// Send Email (basic)
$to = "your@email.com";
$subject = "New Contact Message";
$body = "Name: $name\nEmail: $email\nMessage:\n$message";

mail($to, $subject, $body);

// Redirect
header("Location: index.php?success=1");
exit();
?>