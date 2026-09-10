<?php
    require_once "database_config.php";

    $stmt = $conn->query(
        "SELECT id, passenger_name, destination, fare, created_at
        FROM bookings
        ORDER BY id ASC"
    );

    $bookings = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : array();

    $conn = null;

    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=bookings_export_" . date("Y-m-d") . ".csv");

    // Write directly to the output stream
    $output = fopen("php://output", "w");

    // Header row
    fputcsv($output, array("ID", "Passenger Name", "Destination", "Fare", "Created At"));

    // Data rows
    foreach ($bookings as $booking) {
        fputcsv($output, array(
            $booking["id"],
            $booking["passenger_name"],
            $booking["destination"],
            number_format((float)$booking["fare"], 2),
            $booking["created_at"]
        ));
    }

    fclose($output);
    exit;

  
?>