<?php
// update-appointment-status.php
// Owner AJAX endpoint: update appointment status

session_start();
header('Content-Type: application/json');

include('../../assets/config.php');

// Verify the session and role
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé.']);
    exit();
}

$userId = $_SESSION['uid'];
$sql_role = 'SELECT `role` FROM `users` WHERE `id` = ?';
$stmt_role = mysqli_prepare($conn, $sql_role);
mysqli_stmt_bind_param($stmt_role, 's', $userId);
mysqli_stmt_execute($stmt_role);
$res_role = mysqli_stmt_get_result($stmt_role);
$row_role = mysqli_fetch_assoc($res_role);

if (!$row_role || $row_role['role'] !== 'owner') {
    echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
    exit();
}

$appt_id = intval($_POST['appt_id'] ?? 0);
$status   = trim($_POST['status']   ?? '');

$allowed_statuses = ['pending', 'approved', 'rejected'];
if ($appt_id <= 0 || !in_array($status, $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Paramètres invalides.']);
    exit();
}

$sql = "UPDATE `appointments` SET `status` = ? WHERE `id` = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'si', $status, $appt_id);
$done = mysqli_stmt_execute($stmt);

if ($done) {
    echo json_encode(['success' => true, 'message' => 'Statut mis à jour.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Échec de la mise à jour.']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
