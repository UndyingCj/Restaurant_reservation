<?php
session_start();
require 'php/db_connect.php';

if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.html");
    exit;
}

// 👇 SEARCH HANDLER
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $stmt = $pdo->prepare("
        SELECT reservations.*, users.name 
        FROM reservations 
        JOIN users ON reservations.user_id = users.id 
        WHERE users.name LIKE ?
        ORDER BY res_date, res_time
    ");
    $stmt->execute(['%' . $search . '%']);
} else {
    $stmt = $pdo->query("
        SELECT reservations.*, users.name 
        FROM reservations 
        JOIN users ON reservations.user_id = users.id 
        ORDER BY res_date, res_time
    ");
}
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h1>Admin Panel - CJberto Grill</h1>

  <section class="all-reservations">
    <h2>All Reservations</h2>

    <!-- 👇 SEARCH FORM -->
    <form method="get" style="margin-bottom: 20px; display: flex; gap: 10px;">
      <input type="text" name="search" placeholder="Search by user name..." 
             value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
      <button type="submit">🔍 Search</button>
    </form>

    <?php if (count($reservations) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>User</th>
          <th>Date</th>
          <th>Time</th>
          <th>People</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reservations as $res): ?>
        <tr>
          <td><?php echo htmlspecialchars($res['name']); ?></td>
          <td><?php echo $res['res_date']; ?></td>
          <td><?php echo $res['res_time']; ?></td>
          <td><?php echo $res['people']; ?></td>
          <td>
            <form method="post" action="php/delete_reservation.php" onsubmit="return confirm('Are you sure you want to delete this reservation?');">
              <input type="hidden" name="res_id" value="<?php echo $res['id']; ?>">
              <button type="submit" class="delete-btn">❌ Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
      <p>No reservations found.</p>
    <?php endif; ?>
  </section>

  <p style="text-align: center; margin-top: 20px;">
    <a href="php/logout.php" style="color: #e74c3c; text-decoration: none; font-weight: bold;">👀 Logout</a>
  </p>
</body>
</html>