<?php
    require_once 'database_config.php';

    $destination = trim($_GET["destination"] ?? "");
    $destination = strip_tags($destination);

    $bookings = array();
    $search_performed = isset($_GET["destination"]);

    if ($search_performed && $destination !== "") {
        $search_term = "%" . $destination . "%";

        $stmt = $conn->prepare(
            "SELECT id, passenger_name, destination, fare
            FROM bookings
            WHERE destination LIKE ?
            ORDER BY id ASC"
        );

        if ($stmt) {
            $stmt->bind_param("s", $search_term);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }

            $stmt->close();
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bookings</title>
</head>
<body>
    <h2>Search Bookings by Destination</h2>
    
    <form action="search.php" method="GET">
        <label>Destination:</label>
        <input type="text" name="destination" value="<?php echo htmlspecialchars($destination); ?>"
                maxlength="100" required>
        <input type="submit" value="Search">
    </form>

    <?php if ($search_performed) { ?>

    <h3>Search Results</h3>

    <?php if ($destination === "") { ?>

        <p>Please provide a destination.</p>

    <?php } elseif (count($bookings) === 0) { ?>

        <p>No bookings were found for "<?php echo htmlspecialchars($destination); ?>".</p>
     <?php } else { ?>

        <table border="1">
            <tr>
                <th>ID</th>
                <th>Passenger Name</th>
                <th>Destination</th>
                <th>Fare</th>
            </tr>

            <?php foreach ($bookings as $booking) { ?>
                <tr>
                    <td><?php echo (int)$booking["id"]; ?></td>
                    <td><?php echo htmlspecialchars($booking["passenger_name"]); ?></td>
                    <td><?php echo htmlspecialchars($booking["destination"]); ?></td>
                    <td>
                        <?php if ((float)$booking["fare"] > 500) { ?>
                            <font color="red"><b>R <?php echo number_format((float)$booking["fare"], 2); ?></b></font>
                        <?php } else { ?>
                            R <?php echo number_format((float)$booking["fare"], 2); ?>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>

    <?php } ?>

    <?php } ?>

    <p><a href="index.php">Add a Booking</a></p>
    <p><a href="view_bookings.php">View All Bookings</a></p>
    <p><a href="summary_report.php">Summary Report</a></p>
</body>
</html>
<?php $conn->close(); ?>