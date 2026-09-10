<?php

    /**Loads MySQL settings from the .env file.
     * Refer to .env.example for config reference
    * No database password is written directly into the phph source code
    */

    function loadEnv($file) {
        if (!file_exists($file)) {
            die("Error: .env file was not found.");
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === "" || strpos($line, "#") === 0) {
                continue;
            } 

            $parts =explode("=", $line, 2);

            if (count($parts) !== 2) {
                continue;
            }

            $key = trim($parts[0]);
            $value = trim($parts[1]);

            $_ENV[$key] = $value;
        }
    }

    loadEnv(__DIR__ . "/.env");

    $db_host = $_ENV["DB_HOST"];
    $db_port = (int)$_ENV["DB_PORT"];
    $db_name = $_ENV["DB_NAME"];
    $db_user = $_ENV["DB_USER"];
    $db_pass = $_ENV["DB_PASS"];

    $conn = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name", $db_name, $db_pass);

    if ($conn->connect_error) {
        die("Database connection failed.");
    }

    $conn->set_charset("utf9mb4");
?>