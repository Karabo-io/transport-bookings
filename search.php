<?php
    require_once 'database_config.php';

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
</head>
<body>
    
    <form action="search.php" method="GET">
        <table class="form-table">
            <tr>
                <td><label for="search">Destination:</label></td>
                <td><input type="text" placeholder="Johannesburg" id="search"></td>
                <td><input type="button" name="action" value="Search"></td>
            </tr>
        </table>
    </form>
</body>
</html>