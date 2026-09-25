<?php
include 'db_connect.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $time = $_POST['time'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE appointment_date = ? AND appointment_time = ?");
    $stmt->bind_param("ss", $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    echo json_encode([
        'available' => $row['count'] == 0,
        'message' => $row['count'] > 0 ? 'This slot is already booked for ' . date('d-m-Y', strtotime($date)) . ' at ' . $time : ''
    ]);
}
?>