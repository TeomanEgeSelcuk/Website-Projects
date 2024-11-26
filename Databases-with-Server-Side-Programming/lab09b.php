<?php

// lab09b.php

// Database connection settings
$host = 'localhost';
$username = '';
$password = '';
$dbname = 'tselcuk';

// Connect to the database
$connect = new mysqli($host, $username, $password, $dbname);

// Check for connection errors
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
echo "<div>Connected to MySQL Database <b>$dbname</b></div><br>";

// Query to get all records sorted by date_taken descending
$sql = "SELECT * FROM photographs ORDER BY date_taken DESC";
$result = $connect->query($sql);

// Define the base URL for images
$baseImageUrl = 'https://webdev.cs.torontomu.ca/~tselcuk/CPS_510_img_uploads/';

?>

<!DOCTYPE html>
<html>
<head>
    <title>All Photographs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
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
        }

        .photo img {
            width: 300px; /* Set a fixed width */
            height: 200px; /* Set a fixed height */
            object-fit: cover; /* Ensures the image fills the dimensions without distortion */
            border-radius: 5px;
            margin-top: 10px;
        }

        .photo h2 {
            margin-bottom: 10px;
            color: #007bff;
        }

        .photo p {
            margin: 5px 0;
        }

        .photo p strong {
            color: #333;
        }

        .no-photos {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="photo-container">
        <h1>All Photographs</h1>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Extract the filename from picture_url
                $fileName = basename($row['picture_url']);
                
                // Construct the absolute server path to check if the image exists
                $serverImagePath = __DIR__ . '/CPS_510_img_uploads/' . $fileName;

                echo '<div class="photo">';
                echo '<h2>Picture Number: ' . htmlspecialchars($row['picture_number']) . ' - ' . htmlspecialchars($row['subject']) . '</h2>';
                echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>';
                echo '<p><strong>Date Taken:</strong> ' . htmlspecialchars($row['date_taken']) . '</p>';
                echo '<p><strong>Picture URL:</strong> <a href="' . htmlspecialchars($baseImageUrl . $fileName) . '" target="_blank">' . htmlspecialchars($baseImageUrl . $fileName) . '</a></p>';

                // Check if the image exists before displaying
                if (file_exists($serverImagePath)) {
                    echo '<img src="' . htmlspecialchars($baseImageUrl . $fileName) . '" alt="' . htmlspecialchars($row['subject']) . '">';
                } else {
                    echo '<p style="color:red;">Image not found: ' . htmlspecialchars($baseImageUrl . $fileName) . '</p>';
                }

                echo '</div>';
            }
        } else {
            echo '<p class="no-photos">No photographs found.</p>';
        }

        // Close the database connection
        $connect->close();
        ?>
    </div>
</body>
</html>
