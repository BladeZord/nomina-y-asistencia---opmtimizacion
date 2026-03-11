<?php
include 'includes/session.php';

if (isset($_POST['delete'])) {
    if (!validate_csrf()) {
        $_SESSION['error'] = 'Solicitud no válida. Vuelva a intentar.';
        header('Location: employee.php');
        exit;
    }

    $id = (int) ($_POST['id'] ?? 0);
    if ($id < 1) {
        $_SESSION['error'] = 'Seleccione un empleado para eliminar';
        header('Location: employee.php');
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Empleado eliminado correctamente';
    } else {
        $_SESSION['error'] = 'Error al eliminar. Vuelva a intentar.';
    }
    $stmt->close();
} else {
    $_SESSION['error'] = 'Seleccione un empleado para eliminar';
}

header('Location: employee.php');
exit;
