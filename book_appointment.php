<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $doctor_id = intval($_POST['doctor_id']);
    $appointment_date = $_POST['appointment_date'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO appointments (user_id, doctor_id, appointment_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $doctor_id, $appointment_date);

    if ($stmt->execute()) {
        header("Location: dashboard.html?success=Appointment+booked+successfully");
    } else {
        header("Location: dashboard.html?error=Could+not+book+appointment");
    }

    $stmt->close();
}
?>