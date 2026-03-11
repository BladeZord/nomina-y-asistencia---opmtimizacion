<?php
include 'includes/session.php';

if (isset($_POST['upload'])) {
    if (!validate_csrf()) {
        $_SESSION['error'] = 'Solicitud no válida. Vuelva a intentar.';
        header('Location: employee.php');
        exit;
    }

    $empid = (int) ($_POST['id'] ?? 0);
    if ($empid < 1) {
        $_SESSION['error'] = 'Seleccione un empleado para actualizar la foto';
        header('Location: employee.php');
        exit;
    }

    $photo_filename = '';
    if (!empty($_FILES['photo']['name'])) {
        $uploaded = handle_photo_upload($_FILES['photo'], __DIR__ . '/../images');
        if ($uploaded !== null) {
            $photo_filename = $uploaded;
        }
    }

    if ($photo_filename === '') {
        $_SESSION['error'] = 'Seleccione una imagen válida (JPG, PNG o GIF)';
        header('Location: employee.php');
        exit;
    }

    $stmt = $conn->prepare("UPDATE employees SET photo = ? WHERE id = ?");
    $stmt->bind_param('si', $photo_filename, $empid);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Foto del empleado actualizada correctamente';
    } else {
        $_SESSION['error'] = 'Error al actualizar. Vuelva a intentar.';
    }
    $stmt->close();
} else {
    $_SESSION['error'] = 'Seleccione un empleado para actualizar la foto';
}

header('Location: employee.php');
exit;
