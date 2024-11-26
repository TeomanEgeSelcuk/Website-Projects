<?php

// lab09a.php

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


/*
// SQL to drop the table
$table_name = 'photographs'; // Define the table name to be dropped
$sql_drop_table = "DROP TABLE IF EXISTS $table_name"; // SQL query to drop the table

// Execute the query and handle the result
if ($connect->query($sql_drop_table) === TRUE) {
    // Display success message if the table is dropped successfully
    echo "Table '$table_name' and all its contents have been deleted successfully.<br>";
} else {
    // Display error message if the table cannot be dropped
    echo "Error deleting table: " . $connect->error . "<br>";
}
*/



// Create table if it doesn't exist with UNIQUE constraint on picture_url
$table_sql = "CREATE TABLE IF NOT EXISTS photographs (
    picture_number INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(255),
    location VARCHAR(255),
    date_taken DATE,
    picture_url VARCHAR(255) UNIQUE
)";

if ($connect->query($table_sql) === TRUE) {
    echo "Table 'photographs' created or already exists.<br><br>";
} else {
    die("Error creating table: " . $connect->error . "<br><br>");
}

// Records to insert
$records = [
    ['subject' => 'Northern Lights', 'location' => 'Yukon', 'date_taken' => '2021-03-15', 'picture_url' => './CPS_510_img_uploads/northern_lights.jpg'],
    ['subject' => 'Banff National Park in Winter', 'location' => 'Alberta', 'date_taken' => '2020-12-10', 'picture_url' => './CPS_510_img_uploads/banff_winter.jpg'],
    ['subject' => 'Fall Leaves in Ontario', 'location' => 'Ontario', 'date_taken' => '2020-10-05', 'picture_url' => './CPS_510_img_uploads/fall_leaves_ontario.webp'],
    ['subject' => 'Distillery District', 'location' => 'Toronto, Ontario', 'date_taken' => '2021-07-20', 'picture_url' => './CPS_510_img_uploads/distillery_district.jpeg'],
    ['subject' => 'Icebergs in Newfoundland', 'location' => 'Newfoundland and Labrador', 'date_taken' => '2019-05-15', 'picture_url' => './CPS_510_img_uploads/icebergs_newfoundland.jpeg'],
    ['subject' => 'Wildlife in British Columbia', 'location' => 'British Columbia', 'date_taken' => '2021-08-25', 'picture_url' => './CPS_510_img_uploads/wildlife_bc.webp'],
    ['subject' => 'CN Tower at Dusk', 'location' => 'Ontario', 'date_taken' => '2021-09-30', 'picture_url' => './CPS_510_img_uploads/cn_tower_dusk.webp'],
    ['subject' => 'Prairies in Saskatchewan', 'location' => 'Saskatchewan', 'date_taken' => '2020-07-18', 'picture_url' => './CPS_510_img_uploads/prairies_saskatchewan.jpeg'],
    ['subject' => 'Old Quebec in the Snow', 'location' => 'Quebec', 'date_taken' => '2019-12-12', 'picture_url' => './CPS_510_img_uploads/old_quebec_snow.jpg'],
    ['subject' => 'Rocky Mountains at Sunset', 'location' => 'Alberta', 'date_taken' => '2021-09-05', 'picture_url' => './CPS_510_img_uploads/rocky_mountains_sunset.jpg'],
];

// Define the upload directory
$upload_dir = __DIR__ . '/CPS_510_img_uploads/';

// Function to validate if the image file exists and is valid
function is_valid_image($file_path) {
    if (!file_exists($file_path)) {
        return false;
    }
    $image_info = getimagesize($file_path);
    return $image_info !== false;
}

// Step 1: Insert or update records using ON DUPLICATE KEY UPDATE
$insert_sql = "INSERT INTO photographs (subject, location, date_taken, picture_url) 
               VALUES (?, ?, ?, ?)
               ON DUPLICATE KEY UPDATE 
                   subject = VALUES(subject),
                   location = VALUES(location),
                   date_taken = VALUES(date_taken)";
$insert_stmt = $connect->prepare($insert_sql);

if ($insert_stmt === false) {
    die("Prepare failed for insertion: " . $connect->error . "<br>");
}

$insert_stmt->bind_param("ssss", $subject, $location, $date_taken, $picture_url);

// Insert each record if the image exists and is valid
foreach ($records as $record) {
    $subject = $record['subject'];
    $location = $record['location'];
    $date_taken = $record['date_taken'];
    $picture_url = $record['picture_url'];

    // Build the absolute path
    $absolute_path = $upload_dir . basename($picture_url);

    // Validate the image
    if (is_valid_image($absolute_path)) {
        if ($insert_stmt->execute()) {
            echo "Record for '<b>$subject</b>' inserted/updated successfully. URL: <a href='https://webdev.cs.torontomu.ca/~tselcuk/" . ltrim($picture_url, './') . "' target='_blank'>" . htmlspecialchars($picture_url) . "</a><br>";
        } else {
            echo "Error inserting/updating record for '<b>$subject</b>': " . $insert_stmt->error . "<br>";
        }
    } else {
        echo "Image for '<b>$subject</b>' not found or cannot be opened. Skipping upload.<br>";
    }
}

$insert_stmt->close();
$connect->close();

?>
