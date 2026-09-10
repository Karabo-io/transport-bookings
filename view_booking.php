<?php

require_once "database_config.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    die("Invalid booking ID. <a href='view_bookings.php'>Return to bookings</a>");
}

$stmt = $conn->prepare(
    "SELECT id, passenger_name, destination, fare, created_at
     FROM bookings
     WHERE id = ?"
);

if (!$stmt) {
    die("Database error. <a href='view_bookings.php'>Return to bookings</a>");
}

$stmt->execute([$id]);

if ($stmt->rowCount() === 0) {
    die("Booking not found. <a href='view_bookings.php'>Return to bookings</a>");
}

$booking = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Details</title>
</head>
<body>

<h2>Booking Details</h2>

<table style="Border:1px">
    <tr><td>ID</td><td><?php echo (int)$booking["id"]; ?></td></tr>
    <tr><td>Passenger Name</td><td><?php echo htmlspecialchars($booking["passenger_name"]); ?></td></tr>
    <tr><td>Destination</td><td><?php echo htmlspecialchars($booking["destination"]); ?></td></tr>
    <tr><td>Fare</td><td>R <?php echo number_format((float)$booking["fare"], 2); ?></td></tr>
    <tr><td>Created At</td><td><?php echo htmlspecialchars($booking["created_at"]); ?></td></tr>
</table>

<p><a href="view_bookings.php">Back to All Bookings</a></p>

</body>
</html>
<?php
$conn = null;
?>