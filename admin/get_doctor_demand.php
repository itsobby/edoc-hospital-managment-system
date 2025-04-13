<?php
include('../connection.php'); // Include your database connection

$query = "SELECT d.docid, d.docname, s.sname AS specialty, COUNT(a.appoid) AS total_appointments
          FROM doctor d
          LEFT JOIN appointment a ON d.docid = a.pid
          LEFT JOIN specialties s ON d.specialties = s.id
          GROUP BY d.docid
          ORDER BY total_appointments DESC
          LIMIT 10"; // Top 10 doctors in demand

$result = $database->query($query);

$doctor_demand = [];
while ($row = $result->fetch_assoc()) {
    $doctor_demand[] = $row;
}

header('Content-Type: application/json');
echo json_encode($doctor_demand);
?>
