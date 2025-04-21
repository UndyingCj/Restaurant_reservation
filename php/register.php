<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $passwordRaw = $_POST['password'];

    // Basic validation
    if (empty($name) || empty($email) || empty($passwordRaw)) {
        echo "<p style='color: red;'>❌ All fields are required.</p>";
        exit;
    }

    $password = password_hash($passwordRaw, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);

        session_start();
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_name'] = $name;

        header("Location: ../Dashboard.php");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<p style='color: red;'>❌ Email already registered. Please use another.</p>";
        } else {
            echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
        }
    }
}
?>