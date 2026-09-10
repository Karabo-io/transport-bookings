<?php
    require_once "database_config.php";

    $result = $conn->query("SELECT id, passenger_name, destination, fare, created_at 
        FROM bookings  
        ORDER BY id ASC");

    $bookings = array();
    $total_fare = 0;

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }

    foreach ($bookings as $booking) {
        $total_fare += (float)$booking["fare"];
    }

    $booking_count = count($bookings);
    $avarage_fare = $booking_count > 0 ? $total_fare / $booking_count : 0; //Calculating the average value 
    $highest_fare = 0;

    foreach ($bookings as $booking) {
        if ((float)$booking["fare"] > $highest_fare){
            $highest_fare = (float)$booking["fare"];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Bookings</title>
</head>
<body>

    <h2>Booking Records</h2>

    <?php if($booking_count === 0) { ?>
        <p>No booking records found.</p>

    <?php } else { ?>
        <tr>
            <th>ID</th>
            <th>Passanger Name</th>
            <th>Destination</th>
            <th>Fare</th>
            <th>Action</th>
        </tr>
        <table>
            <?php foreach ($bookings as $booking) { ?>
            <tr>
                <td><?php echo (int)$booking["id"]; ?></td>
                <td><?php echo htmlspecialchars($booking["passanger_name"]); ?></td>
                <td><?php echo htmlspecialchars($booking["destination"]); ?></td>
                <td>
                    <?php if ((float)$booking["fare"] > 500) { ?>
                    <span style="color:red"><b>R <?php echo number_format((float)$booking["fare"], 2); ?></b></span>
                    <?php } else { ?>
                        R <?php echo number_format((float)$booking["fare"], 2); ?>
                    <?php } ?>
                </td>
                <td><a href="view_booking.php?id=<?php echo (int)$booking["id"]; ?>">View</a></td>
            </tr>
          <?php } ?>
        </table>

            </table>

        <h3>Summary Report</h3>
        <p>Total Fare: R <?php echo number_format($total_fare, 2); ?></p>
        <p>Average Fare: R <?php echo number_format($average_fare, 2); ?></p>
        <p>Highest Fare: R <?php echo number_format($highest_fare, 2); ?></p>

    <?php } ?>

    <p><a href="booking_form.html">Add a Booking</a></p>
    <p><a href="search.php">Search by Destination</a></p>
    <p><a href="summary_report.php">Summary Report</a></p>

</body>
</html>
<?php $conn->close(); ?>