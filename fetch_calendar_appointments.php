<?php
require_once 'includes/db.php';

$events = [];
$res = $conn->query("SELECT a.appointment_date, u.name AS patient, d.name AS doctor FROM appointments a JOIN users u ON u.id = a.user_id JOIN doctors d ON d.id = a.doctor_id");

while ($row = $res->fetch_assoc()) {
    $events[] = [
        'title' => $row['patient'] . " with " . $row['doctor'],
        'start' => $row['appointment_date']
    ];
}
header('Content-Type: application/json');
echo json_encode($events);
?>