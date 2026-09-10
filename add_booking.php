<?php
    require_once 'database_config.php';

    $message = "";
    $message_type = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header("Location: index.php");
        exit;
    }

    $passager_name = trim($_POST["name"] ?? "");
    $destination = trim($_POST["destination"]);
    $fare_input= trim($_POST["fare"]);

    /** Input sanitisation */
    $passager_name = strip_tags($passager_name);
    $destination = strip_tags($destination);

    if ($passager_name === "" || $destination === "" || $fare_input === "") {
        $message = "Error: All fields are required.";
        $message_type = "error";
    } elseif (!is_numeric($fare_input) || (float)$fare_input <= 0) {
        $message = "Error: Fare must be a valid number grater than 0.";
        $message_type = "error";
    } elseif (strlen($passager_name) > 100 || strlen($destination) > 100) {
        $message = "Error: Passanger name and destination must not exceed 100 characters.";
        $message_type = "error";
    } else{
        $fare = (float)$fare_input;

        /** Prepared statement protects the query freom SQL Injection */
        $duplicates_stmt = $conn->prepare("SELECT id FROM bookings WHERE passanger_name = ? AND destination = ? AND fare = ? LIMIT 1");

        if (!$duplicates_stmt) {
            $message = "Error: Database operation could not be prepared.";
            $message_type = "error";
        } else {
            $duplicates_stmt->bind_param("ssd", $passager_name, $destination, $fare);
            $duplicates_stmt->execute();
            $duplicate_result = $duplicates_stmt->get_result();

            if ($duplicate_result->num_rows > 0) {
                $message = "Error: This booking already exists.";
                $message_type = "error";
            } else {
                $insert_stmt = $conn->prepare("INSERT INTO bookings (passanger_name, destination, fare) VALUES (?, ?, ?)");

                if (!$insert_stmt) {
                    $message = "Error: Booking could not be prepared.";
                    $message_type = "error";
                } else {
                    $insert_stmt->bind_param("ssd", $passager_name, $destination, $fare);

                    if ($insert_stmt->execute()) {
                        $message = "Booking added successfully.";
                        $message_type = "success";
                    }else {
                        $message = "Error: Booking could not be saved.";
                        $message_type = "error";
                    }
                    
                    $insert_stmt->close();
                }
            } 
            
            $duplicates_stmt->close();
        }
    }
    $conn->close();
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

    <p><a href="index.php">Back to Booking Form</a></p>
    <p><a href="view_bookings.php">View All Bookings</a></p>
    <p><a href="search_bookings.php">Search by Destination</a></p>
        
</body>
</html>