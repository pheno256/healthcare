<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: Login.html?error=Please+fill+in+all+fields");
        exit;
    }

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            header("Location: home.html?success=Login+successful");
            exit;
        } else {
            header("Location: Login.html?error=Invalid+credentials");
            exit;
        }
    } else {
        header("Location: Login.html?error=No+user+found+with+this+email");
        exit;
    }

    $stmt->close();
}
?>