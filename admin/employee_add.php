<?php
include 'includes/session.php';

if (isset($_POST['add'])) {
    if (!validate_csrf()) {
        $_SESSION['error'] = 'Solicitud no válida. Vuelva a intentar.';
        header('Location: employee.php');
        exit;
    }

    $firstname = trim($_POST['firstname'] ?? '');
    $lastname  = trim($_POST['lastname'] ?? '');
    $address   = trim($_POST['address'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $contact   = trim($_POST['contact'] ?? '');
    $gender    = $_POST['gender'] ?? '';
    $position  = (int) ($_POST['position'] ?? 0);
    $schedule  = (int) ($_POST['schedule'] ?? 0);

    $photo_filename = '';
    if (!empty($_FILES['photo']['name'])) {
        $uploaded = handle_photo_upload($_FILES['photo'], __DIR__ . '/../images');
        if ($uploaded !== null) {
            $photo_filename = $uploaded;
        }
    }

    $letters = implode('', range('A', 'Z'));
    $numbers = implode('', range(0, 9));
    $employee_id = substr(str_shuffle($letters), 0, 3) . substr(str_shuffle($numbers), 0, 9);

    $stmt = $conn->prepare("INSERT INTO employees (employee_id, firstname, lastname, address, birthdate, contact_info, gender, position_id, schedule_id, photo, created_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param('ssssssiiis', $employee_id, $firstname, $lastname, $address, $birthdate, $contact, $gender, $position, $schedule, $photo_filename);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Empleado añadido correctamente';
    } else {
        $_SESSION['error'] = 'Error al guardar. Vuelva a intentar.';
    }
    $stmt->close();
} else {
    $_SESSION['error'] = 'Complete el formulario de alta primero';
}

header('Location: employee.php');
exit;
