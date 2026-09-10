<?php
    require_once 'database_config.php';

    $message = "";
    $message_type = "";

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: booking_form.html");
        exit;
    }

    $passeger_name = trim($_POST["passenger_name"] ?? "");
    $destination = trim($_POST["destination"] ?? "");
    $fare_input= trim($_POST["fare"] ?? "");

    /** Input sanitisation */
    $passeger_name = strip_tags($passeger_name);
    $destination = strip_tags($destination);

    if ($passeger_name === "" || $destination === "" || $fare_input === "") {
        $message = "Error: All fields are required.";
        $message_type = "error";
    } elseif (!is_numeric($fare_input) || (float)$fare_input <= 0) {
        $message = "Error: Fare must be a valid number grater than 0.";
        $message_type = "error";
    } elseif (strlen($passeger_name) > 100 || strlen($destination) > 100) {
        $message = "Error: Passanger name and destination must not exceed 100 characters.";
        $message_type = "error";
    } else{
        $fare = (float)$fare_input;

        $duplicates_stmt = $conn->prepare("SELECT id FROM bookings WHERE passenger_name = ? AND destination = ? AND fare = ? LIMIT 1");

        if (!$duplicates_stmt) {
            $message = "Error: Database operation could not be prepared.";
            $message_type = "error";
        } else {
             $duplicates_stmt->execute([$passeger_name, $destination, $fare]);

            if ($duplicates_stmt->rowCount() > 0) {
                $message = "Error: This booking already exists.";
                $message_type = "error";
            } else {
                $insert_stmt = $conn->prepare("INSERT INTO bookings (passenger_name, destination, fare) VALUES (?, ?, ?)");

                if (!$insert_stmt) {
                    $message = "Error: Booking could not be prepared.";
                    $message_type = "error";
                } else {
                        
                    if ($insert_stmt->execute([$passeger_name, $destination, $fare])) {
                        $message = "Booking added successfully.";
                        $message_type = "success";
                    }else {
                        $message = "Error: Booking could not be saved.";
                        $message_type = "error";
                    }
        
                }
            } 
        }
    }
    $conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Booking Result</title>
</head>
<body>
    <h2><?php echo htmlspecialchars($message); ?></h2>

    <?php if ($message_type === "success") { ?>
    <p>The booking has been recorded in the system.</p>
    <?php } ?>

    <p><a href="booking_form.html">Back to Booking Form</a></p>
    <p><a href="view_bookings.php">View All Bookings</a></p>
    <p><a href="search.php">Search by Destination</a></p>
        
</body>
</html>