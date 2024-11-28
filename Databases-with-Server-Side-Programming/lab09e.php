<?php

// lab09e.php

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

// Check total number of images
$total_result = $connect->query("SELECT COUNT(*) as total FROM photographs");
if (!$total_result) {
    die("Error fetching total number of images: " . $connect->error);
}

$total_row = $total_result->fetch_assoc();
$total_images = $total_row['total'];

// If no images are found, display a message and stop execution
if ($total_images == 0) {
    die("<p style='text-align: center; font-size: 18px; color: #555;'>There are no photos to be found.</p>");
}

// Fetch one random image
$random_result = $connect->query("SELECT * FROM photographs ORDER BY RAND() LIMIT 1");
if (!$random_result) {
    die("Error fetching random image: " . $connect->error);
}

$random_image = $random_result->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Random Photograph</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e8eaf6;
            text-align: center;
        }
        .photo {
            background-color: #fff;
            display: inline-block;
            padding: 15px;
            margin: 15px 0;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .photo img {
            width: 300px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .photo h2 {
            margin: 10px 0;
        }
        .total-images {
            margin-top: 20px;
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Random Photograph</h1>

    <?php
    // Display the random image
    if ($random_image) {
        echo '<div class="photo">';
        echo '<img src="' . htmlspecialchars($random_image['picture_url']) . '" alt="' . htmlspecialchars($random_image['subject']) . '">';
        echo '<h2>' . htmlspecialchars($random_image['subject']) . '</h2>';
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($random_image['location']) . '</p>';
        echo '<p><strong>Date Taken:</strong> ' . htmlspecialchars($random_image['date_taken']) . '</p>';
        echo '</div>';
    } else {
        echo '<p>No photographs found.</p>';
    }
    ?>

    <div class="total-images">
        <p>Total number of images in the database: <?php echo $total_images; ?></p>
    </div>
</body>
</html>

<?php
$connect->close();
?>
