<?php
session_start();
include("../../assets/config.php");

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé.']);
    exit;
}

$id = $_SESSION['id'];
$password = $_POST['current'] ?? '';
$newpassword = $_POST['new'] ?? '';
$confirmnewpassword = $_POST['repeat'] ?? '';

if (empty($password) || empty($newpassword) || empty($confirmnewpassword)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
    exit;
}

$result = mysqli_query($conn, "SELECT password_hash FROM users WHERE id='$id'");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pass = $row['password_hash'];
    
    if (password_verify($password, $pass)) {
        if ($newpassword === $confirmnewpassword) {
            $newpasswordhash = password_hash($newpassword, PASSWORD_DEFAULT);
            if (mysqli_query($conn, "UPDATE users SET password_hash='$newpasswordhash' WHERE id='$id'")) {
                echo json_encode(['success' => true, 'message' => 'Mot de passe mis à jour avec succès!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Les nouveaux mots de passe ne correspondent pas.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Mot de passe actuel incorrect.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
}
?>
