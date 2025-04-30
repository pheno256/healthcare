<?php
require_once 'includes/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email) || empty($password)) {
        header("Location: Register.html?error=Please+fill+in+all+fields");
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['user_name'] = $name;
        header("Location: home.html?success=Registration+successful");
    } else {
        header("Location: Register.html?error=" . urlencode("Error: " . $stmt->error));
    }

    $stmt->close();
}
?>