<?php
session_start();
require 'db_connect.php';

// Only allow access if admin is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: ../admin_login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['res_id'])) {
    $res_id = $_POST['res_id'];

    // Delete the reservation
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->execute([$res_id]);

    // Redirect back to admin dashboard
    header("Location: ../admin_dashboard.php");
    exit;
}
?>