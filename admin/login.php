<?php
session_start();
require_once __DIR__ . '/includes/conn.php';

if (isset($_POST['login'])) {
    if (!validate_csrf()) {
        $_SESSION['error'] = 'Solicitud no válida. Vuelva a intentar.';
        header('Location: index.php');
        exit;
    }
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $_SESSION['error'] = 'Ingrese el nombre de usuario';
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if (!$row) {
            $_SESSION['error'] = 'No existe una cuenta con ese usuario';
        } elseif (password_verify($password, $row['password'])) {
            $_SESSION['admin'] = $row['id'];
        } else {
            $_SESSION['error'] = 'Contraseña incorrecta';
        }
    }
} else {
    $_SESSION['error'] = 'Ingrese sus credenciales de administrador';
}

header('Location: index.php');
exit;
