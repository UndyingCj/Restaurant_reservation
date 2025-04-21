<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = (int) $_POST['people'];

    if (!$date || !$time || $people < 1) {
        echo "❌ Invalid input. Please go back and try again.";
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO reservations (user_id, res_date, res_time, people) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $date, $time, $people]);

        header("Location: ../Dashboard.php?success=1");
    } catch (PDOException $e) {
        echo "❌ Error saving reservation: " . $e->getMessage();
    }
}
?>