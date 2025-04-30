<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $appt_id = intval($_POST['appointment_id']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $appt_id, $user_id);
    $stmt->execute();

    header("Location: dashboard.html?success=Appointment+cancelled");
    exit;
}
?>