<?php
    // summary_report.php
    // Calculates total, average and highest fare using an array and loops.

    require_once "database_config.php";

    $stmt = $conn->query(
        "SELECT passenger_name, destination, fare
        FROM bookings
        ORDER BY id ASC"
    );

    $bookings = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : array();
    $total_fare = 0;
    $highest_fare = 0;
    $highest_booking = null;

    foreach ($bookings as $booking) {
        $fare = (float)$booking["fare"];
        $total_fare += $fare;

        if ($highest_booking === null || $fare > $highest_fare) {
            $highest_fare = $fare;
            $highest_booking = $booking;
        }
    }

    $count = count($bookings);
    $average_fare = $count > 0 ? $total_fare / $count : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Summary Report</title>
</head>
<body>

<h2>Summary Report</h2>

<?php if ($count === 0) { ?>

    <p>No records exist, so a summary report cannot be generated.</p>

<?php } else { ?>

    <p>Total Bookings: <?php echo $count; ?></p>
    <p>Total Fare: R <?php echo number_format($total_fare, 2); ?></p>
    <p>Average Fare: R <?php echo number_format($average_fare, 2); ?></p>
    <p>Highest Fare: R <?php echo number_format($highest_fare, 2); ?></p>

    <h3>Highest Fare Booking</h3>
    <p>Passenger: <?php echo htmlspecialchars($highest_booking["passenger_name"]); ?></p>
    <p>Destination: <?php echo htmlspecialchars($highest_booking["destination"]); ?></p>

<?php } ?>

<p><a href="booking_form.html">Add a Booking</a></p>
<p><a href="view_bookings.php">View All Bookings</a></p>
<p><a href="search.php">Search Bookings</a></p>

</body>
</html>
<?php $conn = null; ?>