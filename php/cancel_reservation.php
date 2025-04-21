<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['res_id'])) {
    $res_id = $_POST['res_id'];
    $user_id = $_SESSION['user_id'];

    // Ensure user only deletes their own reservation
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ? AND user_id = ?");
    $stmt->execute([$res_id, $user_id]);

    header("Location: ../Dashboard.php");
    exit;
}
?>