<?php
require 'php/session_check.php';
require 'php/db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch reservations for this user
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE user_id = ? ORDER BY res_date, res_time");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - CJberto Grill</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>

  <section class="reservation-form">
    <h2>Make a Reservation</h2>
    <form action="php/make_reservation.php" method="post">
      <input type="date" name="date" required>
      <input type="time" name="time" required>
      <input type="number" name="people" placeholder="Number of People" min="1" required>
      <button type="submit">Book Reservation</button>
    </form>
  </section>

  <!-- Reservation List -->
  <section class="user-reservations">
    <h2>Your Reservations</h2>
    <?php if (count($reservations) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>People</th>
            <th>Cancel</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($reservations as $res): ?>
            <tr>
              <td><?php echo htmlspecialchars($res['res_date']); ?></td>
              <td><?php echo htmlspecialchars($res['res_time']); ?></td>
              <td><?php echo $res['people']; ?></td>
              <td>
                <form action="php/cancel_reservation.php" method="post" onsubmit="return confirm('Cancel this reservation?');">
                  <input type="hidden" name="res_id" value="<?php echo $res['id']; ?>">
                  <button type="submit">❌ Cancel</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>You have no reservations yet.</p>
    <?php endif; ?>
  </section>
</body>
</html>