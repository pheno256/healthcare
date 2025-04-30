<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_name']) || $_SESSION['user_name'] !== 'Admin') {
    echo "<p class='error'>Access denied. Admins only.</p>";
    exit;
}

function fetchAll($conn, $query) {
    $result = $conn->query($query);
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

$users = fetchAll($conn, "SELECT id, name, email, created_at FROM users");
$doctors = fetchAll($conn, "SELECT id, name, specialty, location FROM doctors");
$appointments = fetchAll($conn, "
    SELECT a.id, u.name AS patient, d.name AS doctor, a.appointment_date
    FROM appointments a
    JOIN users u ON a.user_id = u.id
    JOIN doctors d ON a.doctor_id = d.id
    ORDER BY a.appointment_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - HealthCare Africa</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <nav class="nav-bar">
    <a href="home.html" class="logo">HealthCare Admin</a>
    <ul class="nav-links">
      <li><a href="calendar.html">Calendar</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>

  <main class="dashboard-container">
    <section class="glass-form">
      <h2>All Users</h2>
      <ul>
        <?php foreach ($users as $user): ?>
          <li>
            <?= htmlspecialchars($user['name']) ?> - <?= $user['email'] ?> (<?= $user['created_at'] ?>)
          </li>
        <?php endforeach; ?>
      </ul>
    </section>

    <section class="glass-form">
      <h2>All Doctors</h2>
      <ul>
        <?php foreach ($doctors as $doc): ?>
          <li>
            <?= htmlspecialchars($doc['name']) ?> - <?= $doc['specialty'] ?> - <?= $doc['location'] ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>

    <section class="glass-form" style="grid-column: span 2;">
      <h2>Appointments</h2>
      <ul>
        <?php foreach ($appointments as $appt): ?>
          <li>
            <?= htmlspecialchars($appt['patient']) ?> with <?= htmlspecialchars($appt['doctor']) ?> on <?= $appt['appointment_date'] ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>
  </main>
</body>
</html>
