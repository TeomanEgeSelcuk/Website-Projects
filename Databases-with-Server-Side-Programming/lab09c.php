<?php

// lab09c.php

// Database connection settings
$host = 'localhost';
$username = '';
$password = '';
$dbname = '';

// Connect to the database
$connect = new mysqli($host, $username, $password, $dbname);

// Check for connection errors
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
echo "<div>Connected to MySQL Database <b>$dbname</b></div><br>";

// Begin: Code to delete all files and ensure no photographs in Ontario
// This block is for development/testing purposes. Comment this out in production.

/* START DEVELOPMENT BLOCK 

// Query to delete all records where location is Ontario
$deleteSQL = "DELETE FROM photographs WHERE location = 'Ontario'";
if ($connect->query($deleteSQL) === TRUE) {
    echo "<div>All records in Ontario have been deleted successfully.</div><br>";
} else {
    echo "<div>Error deleting records in Ontario: " . $connect->error . "</div><br>";
}

 END DEVELOPMENT BLOCK */

// Query to get records where location is Ontario
$sql = "SELECT * FROM photographs WHERE location = 'Ontario'";
$result = $connect->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Ontario Photographs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
        }

        .photo-container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .photo {
            background-color: #fff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
        }

        .photo img {
            width: 300px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .photo h2 {
            margin: 10px 0;
            color: #007bff;
        }

        .photo p {
            margin: 5px 0;
            color: #555;
        }

        .no-photos {
            text-align: center;
            font-size: 18px;
            color: #555;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="photo-container">
        <h1>Photographs Taken in Ontario</h1>
        <?php
        // Ensure $result is valid before processing
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="photo">';
                echo '<img src="' . htmlspecialchars($row['picture_url']) . '" alt="' . htmlspecialchars($row['subject']) . '">';
                echo '<h2>' . htmlspecialchars($row['subject']) . '</h2>';
                echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>';
                echo '</div>';
            }
        } else {
            // This block will execute when there are no Ontario photographs
            echo '<p class="no-photos">No photographs taken in Ontario.</p>';
        }
        ?>
    </div>
</body>
</html>

<?php

// Close the database connection
$connect->close();

?>
