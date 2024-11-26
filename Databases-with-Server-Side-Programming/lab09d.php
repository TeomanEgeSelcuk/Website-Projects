<?php

// lab09d.php

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

// Get distinct locations
$locations_result = $connect->query("SELECT DISTINCT location FROM photographs");
$locations = [];
while ($row = $locations_result->fetch_assoc()) {
    $locations[] = $row['location'];
}

// Get distinct years from date_taken
$years_result = $connect->query("SELECT DISTINCT YEAR(date_taken) as year FROM photographs");
$years = [];
while ($row = $years_result->fetch_assoc()) {
    $years[] = $row['year'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Photographs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff3e0;
        }
        .form-container, .photo-container {
            width: 80%;
            margin: auto;
        }
        .photo {
            background-color: #fff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .photo img {
            width: 300px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .form-container form {
            background-color: #ffe0b2;
            padding: 15px;
            border-radius: 5px;
        }
        .form-container label {
            margin-right: 10px;
        }
        .form-container select {
            margin-right: 20px;
        }
        .form-container input[type="submit"] {
            padding: 5px 15px;
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
    <div class="form-container">
        <h1>Search Photographs</h1>
        <form method="get" action="">
            <label for="location">Location:</label>
            <select name="location" id="location">
                <option value="">All</option>
                <?php
                foreach ($locations as $location) {
                    $selected = (isset($_GET['location']) && $_GET['location'] == $location) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($location) . '" ' . $selected . '>' . htmlspecialchars($location) . '</option>';
                }
                ?>
            </select>
            <label for="year">Year:</label>
            <select name="year" id="year">
                <option value="">All</option>
                <?php
                foreach ($years as $year) {
                    $selected = (isset($_GET['year']) && $_GET['year'] == $year) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($year) . '" ' . $selected . '>' . htmlspecialchars($year) . '</option>';
                }
                ?>
            </select>
            <input type="submit" value="Search">
        </form>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['location']) || isset($_GET['year']))) {
        $location = isset($_GET['location']) && $_GET['location'] != '' ? $connect->real_escape_string($_GET['location']) : '';
        $year = isset($_GET['year']) && $_GET['year'] != '' ? (int)$_GET['year'] : '';

        $conditions = [];
        if ($location != '') {
            $conditions[] = "location = '$location'";
        }
        if ($year != '') {
            $conditions[] = "YEAR(date_taken) = $year";
        }

        $where_clause = '';
        if (count($conditions) > 0) {
            $where_clause = 'WHERE ' . implode(' AND ', $conditions);
        }

        $sql = "SELECT * FROM photographs $where_clause";
        $result = $connect->query($sql);

        echo '<div class="photo-container">';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="photo">';
                echo '<img src="' . htmlspecialchars($row['picture_url']) . '" alt="' . htmlspecialchars($row['subject']) . '">';
                echo '<h2>' . htmlspecialchars($row['subject']) . '</h2>';
                echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>';
                echo '<p><strong>Date Taken:</strong> ' . htmlspecialchars($row['date_taken']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p class="no-photos">No photographs found for the selected criteria.</p>';
        }
        echo '</div>';
    }
    ?>
</body>
</html>

<?php
$connect->close();
?>
