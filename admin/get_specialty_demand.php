<?php
include('../connection.php'); // Include your database connection

$query = "SELECT s.sname AS specialty, COUNT(a.appoid) AS total_appointments
          FROM appointment a
          JOIN doctor d ON a.pid = d.docid
          JOIN specialties s ON d.specialties = s.id
          GROUP BY s.sname
          ORDER BY total_appointments DESC";

$result = $database->query($query);

$specialty_demand = [];
while ($row = $result->fetch_assoc()) {
    $specialty_demand[] = $row;
}

header('Content-Type: application/json');
echo json_encode($specialty_demand);
?>
