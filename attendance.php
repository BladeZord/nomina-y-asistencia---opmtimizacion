<?php
$output = ['error' => false, 'message' => ''];

if (!isset($_POST['employee']) || !isset($_POST['status'])) {
    echo json_encode($output);
    exit;
}

require_once __DIR__ . '/config/init.php';

$employee = trim($_POST['employee']);
$status   = $_POST['status'] === 'out' ? 'out' : 'in';

if ($employee === '') {
    $output['error'] = true;
    $output['message'] = 'ID de empleado no encontrado';
    echo json_encode($output);
    exit;
}

// Buscar empleado (prepared statement)
$stmt = $conn->prepare("SELECT id, firstname, lastname, schedule_id FROM employees WHERE employee_id = ?");
$stmt->bind_param('s', $employee);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();

if ($res->num_rows === 0) {
    $output['error'] = true;
    $output['message'] = 'ID de empleado no encontrado';
    echo json_encode($output);
    exit;
}

$row = $res->fetch_assoc();
$id = (int) $row['id'];
$date_now = date('Y-m-d');

if ($status === 'in') {
    $st = $conn->prepare("SELECT 1 FROM attendance WHERE employee_id = ? AND date = ? AND time_in IS NOT NULL");
    $st->bind_param('is', $id, $date_now);
    $st->execute();
    $q = $st->get_result();
    $st->close();
    if ($q->num_rows > 0) {
        $output['error'] = true;
        $output['message'] = 'Has registrado tu entrada por hoy';
        echo json_encode($output);
        exit;
    }
    $sched = (int) $row['schedule_id'];
    $st = $conn->prepare("SELECT time_in FROM schedules WHERE id = ?");
    $st->bind_param('i', $sched);
    $st->execute();
    $srow = $st->get_result()->fetch_assoc();
    $st->close();
    $lognow = date('H:i:s');
    $logstatus = ($srow && $lognow > $srow['time_in']) ? 0 : 1;

    $ins = $conn->prepare("INSERT INTO attendance (employee_id, date, time_in, status, time_out, num_hr) VALUES (?, ?, NOW(), ?, '00:00:00', 0)");
    $ins->bind_param('isi', $id, $date_now, $logstatus);
    if ($ins->execute()) {
        $output['message'] = 'Llegada: ' . $row['firstname'] . ' ' . $row['lastname'];
    } else {
        $output['error'] = true;
        $output['message'] = 'Error al registrar. Intente de nuevo.';
    }
    $ins->close();
    echo json_encode($output);
    exit;
}

// status === 'out'
$st = $conn->prepare("SELECT attendance.id AS uid, time_out, firstname, lastname FROM attendance LEFT JOIN employees ON employees.id = attendance.employee_id WHERE attendance.employee_id = ? AND date = ?");
$st->bind_param('is', $id, $date_now);
$st->execute();
$q = $st->get_result();
$st->close();

if ($q->num_rows < 1) {
    $output['error'] = true;
    $output['message'] = 'No se puede registrar tu salida sin haber registrado antes tu entrada.';
    echo json_encode($output);
    exit;
}

$row = $q->fetch_assoc();
if ($row['time_out'] !== '00:00:00' && $row['time_out'] !== null) {
    $output['error'] = true;
    $output['message'] = 'Has registrado tu salida correctamente hoy.';
    echo json_encode($output);
    exit;
}

$uid = (int) $row['uid'];
$up = $conn->prepare("UPDATE attendance SET time_out = NOW() WHERE id = ?");
$up->bind_param('i', $uid);
if (!$up->execute()) {
    $output['error'] = true;
    $output['message'] = 'Error al registrar. Intente de nuevo.';
    echo json_encode($output);
    exit;
}
$up->close();

$output['message'] = 'Salida: ' . $row['firstname'] . ' ' . $row['lastname'];

// Calcular num_hr
$st = $conn->prepare("SELECT time_in, time_out FROM attendance WHERE id = ?");
$st->bind_param('i', $uid);
$st->execute();
$urow = $st->get_result()->fetch_assoc();
$st->close();

$st = $conn->prepare("SELECT time_in, time_out FROM employees LEFT JOIN schedules ON schedules.id = employees.schedule_id WHERE employees.id = ?");
$st->bind_param('i', $id);
$st->execute();
$srow = $st->get_result()->fetch_assoc();
$st->close();

$time_in  = $urow['time_in'];
$time_out = $urow['time_out'];
if ($srow && $srow['time_in'] && $srow['time_in'] > $urow['time_in']) {
    $time_in = $srow['time_in'];
}
if ($srow && $srow['time_out'] && $srow['time_out'] < $urow['time_in']) {
    $time_out = $srow['time_out'];
}

$t1 = new DateTime($time_in);
$t2 = new DateTime($time_out);
$interval = $t1->diff($t2);
$hrs = (float) $interval->format('%h');
$mins = (float) $interval->format('%i') / 60;
$int = $hrs + $mins;
if ($int > 4) {
    $int = $int - 1;
}

$up2 = $conn->prepare("UPDATE attendance SET num_hr = ? WHERE id = ?");
$up2->bind_param('di', $int, $uid);
$up2->execute();
$up2->close();

echo json_encode($output);
