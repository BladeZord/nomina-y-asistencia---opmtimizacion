<?php
include 'includes/session.php';

if (isset($_POST['edit'])) {
    if (!validate_csrf()) {
        $_SESSION['error'] = 'Solicitud no válida. Vuelva a intentar.';
        header('Location: employee.php');
        exit;
    }

    $empid    = (int) ($_POST['id'] ?? 0);
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname  = trim($_POST['lastname'] ?? '');
    $address   = trim($_POST['address'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $contact   = trim($_POST['contact'] ?? '');
    $gender    = $_POST['gender'] ?? '';
    $position  = (int) ($_POST['position'] ?? 0);
    $schedule  = (int) ($_POST['schedule'] ?? 0);

    if ($empid < 1) {
        $_SESSION['error'] = 'Seleccione un empleado para editar';
        header('Location: employee.php');
        exit;
    }

    $stmt = $conn->prepare("UPDATE employees SET firstname = ?, lastname = ?, address = ?, birthdate = ?, contact_info = ?, gender = ?, position_id = ?, schedule_id = ? WHERE id = ?");
    $stmt->bind_param('ssssssiii', $firstname, $lastname, $address, $birthdate, $contact, $gender, $position, $schedule, $empid);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Empleado actualizado correctamente';
    } else {
        $_SESSION['error'] = 'Error al actualizar. Vuelva a intentar.';
    }
    $stmt->close();
} else {
    $_SESSION['error'] = 'Seleccione un empleado para editar';
}

header('Location: employee.php');
exit;
