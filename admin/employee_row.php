<?php
include 'includes/session.php';

if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    if ($id < 1) {
        echo json_encode([]);
        exit;
    }
    $stmt = $conn->prepare("SELECT *, employees.id AS empid FROM employees LEFT JOIN position ON position.id = employees.position_id LEFT JOIN schedules ON schedules.id = employees.schedule_id WHERE employees.id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    echo json_encode($row ?: []);
}
