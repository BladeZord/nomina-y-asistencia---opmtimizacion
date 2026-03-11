<?php
session_start();
require_once __DIR__ . '/conn.php';

if (!isset($_SESSION['admin']) || trim((string) $_SESSION['admin']) === '') {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
$aid = $_SESSION['admin'];
$stmt->bind_param('s', $aid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    unset($_SESSION['admin']);
    header('Location: index.php');
    exit;
}
