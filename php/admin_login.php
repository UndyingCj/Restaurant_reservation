<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    echo "<pre>";
    print_r($admin);
    echo "</pre>";

    echo "<br>Typed password: $password<br>";

    if ($admin && password_verify($password, $admin['password'])) {
        echo "✅ Password verified.";
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: ../admin_dashboard.php");
        exit;
    } else {
        echo "<p style='color:red;'>❌ Invalid admin login</p>";
    }
}
?>