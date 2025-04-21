<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    // Basic validation
    if (empty($email) || empty($password)) {
        echo "<p style='color: red;'>❌ Email and password are required.</p>";
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: ../Dashboard.php");
        exit;
    } else {
        echo "<p style='color: red;'>❌ Invalid email or password.</p>";
    }
}
?>