<?php 
// lab08.php

// Enable error reporting for debugging (Remove these lines in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set timezone
date_default_timezone_set('America/New_York');

// Problem 1: Time-based greeting and background image
$hour = date('H');

// Determine greeting and background image based on the current hour
if ($hour >= 6 && $hour < 12) {
    $greeting = "Good morning";
    $bg_image = ".background-images/sun2.jpg";
    $font_color = "#000000"; // Black text
} elseif ($hour >= 12 && $hour < 18) {
    $greeting = "Good afternoon";
    $bg_image = "./background-images/sky.gif";
    $font_color = "#FFFFFF"; // White text
} elseif ($hour >= 18 && $hour < 21) {
    $greeting = "Good evening";
    $bg_image = "background-images/spring.gif";
    $font_color = "#FFFFFF"; // White text
} else {
    $greeting = "Good night";
    $bg_image = "background-images/night_sky.gif";
    $font_color = "#FFFFFF"; // White text
}

// Problem 2: Multiplication table processing
$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 0;
$columns = isset($_GET['columns']) ? intval($_GET['columns']) : 0;
$table_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rows'], $_GET['columns'])) {
    if ($rows < 3 || $rows > 12) {
        $table_error = "Number of rows must be between 3 and 12.";
    } elseif ($columns < 3 || $columns > 12) {
        $table_error = "Number of columns must be between 3 and 12.";
    }
}

// Problem 3: Favorite image selection
if (isset($_POST['favorite_image'])) {
    $favorite_image = $_POST['favorite_image'];
    setcookie('favorite_image', $favorite_image, time() + 86400); // 86400 seconds = 24 hours
    header("Location: lab08.php");
    exit;
}

$favorite_image = isset($_COOKIE['favorite_image']) ? $_COOKIE['favorite_image'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Assignment</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        #greeting {
            background-image: url('<?php echo htmlspecialchars($bg_image); ?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            height: 300px;
            position: relative;
            color: <?php echo htmlspecialchars($font_color); ?>;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #greeting h1 {
            font-size: 48px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        #favorite-image {
            position: fixed;
            top: 10px;
            right: 10px;
            opacity: 0.8;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.7);
            padding: 10px;
            border-radius: 8px;
        }
        #favorite-image img {
            max-width: 150px;
            height: auto;
            border-radius: 4px;
        }
        #favorite-image p {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0 0 0;
            color: #333;
        }
        #content {
            padding: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
            background-color: #f2f2f2;
        }
        tr:nth-child(even) td {
            background-color: #e6e6e6;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: inline-block;
            width: 200px;
            margin-bottom: 10px;
        }
        input[type="number"], input[type="radio"] {
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .welcome-message {
            font-size: 20px;
            margin: 20px;
        }
    </style>
</head>
<body>
    <!-- Display the selected favorite image -->
    <?php if ($favorite_image): ?>
        <div id="favorite-image">
            <img src="images/<?php echo htmlspecialchars($favorite_image); ?>" alt="Favorite Image">
            <p>Current image: <?php echo htmlspecialchars($favorite_image); ?></p>
        </div>
    <?php else: ?>
        <div class="welcome-message">
            Welcome! Please select your favorite image.
        </div>
    <?php endif; ?>

    <!-- Problem 1: Greeting Section -->
    <div id="greeting" style="background-image: url('<?php echo htmlspecialchars($bg_image); ?>');">
        <!-- Background Image Path: <?php echo htmlspecialchars($bg_image); ?> -->
        <h1><?php echo htmlspecialchars($greeting); ?></h1>
    </div>

    <div id="content">
        <!-- Problem 2: Multiplication Table Form -->
        <h2>Multiplication Table Generator</h2>
        <form method="get" action="lab08.php">
            <label for="rows">Enter number of rows (3-12): </label>
            <input type="number" id="rows" name="rows" min="3" max="12" required>
            <br>
            <label for="columns">Enter number of columns (3-12): </label>
            <input type="number" id="columns" name="columns" min="3" max="12" required>
            <br>
            <input type="submit" value="Generate Table">
        </form>

        <!-- Display Multiplication Table -->
        <?php if ($_SERVER['REQUEST_METHOD'] === 'GET' && !$table_error): ?>
            <h3>Generated Multiplication Table</h3>
            <table>
                <?php for ($i = 1; $i <= $rows; $i++): ?>
                    <tr>
                        <?php for ($j = 1; $j <= $columns; $j++): ?>
                            <td><?php echo $i * $j; ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        <?php elseif ($table_error): ?>
            <p class="error"><?php echo htmlspecialchars($table_error); ?></p>
        <?php endif; ?>

        <!-- Problem 3: Favorite Image Selection Form -->
        <h2>Select Your Favorite Image</h2>
        <form method="post" action="lab08.php">
            <label>Select your favorite image:</label><br>
            <input type="radio" id="table" name="favorite_image" value="table.gif" required>
            <label for="table">Table</label><br>
            <input type="radio" id="turkey5" name="favorite_image" value="turkey5.gif">
            <label for="turkey5">Turkey 5</label><br>
            <input type="radio" id="turkey3" name="favorite_image" value="turkey3.gif">
            <label for="turkey3">Turkey 3</label><br>
            <input type="radio" id="thanks2" name="favorite_image" value="thanks2.gif">
            <label for="thanks2">Thanks 2</label><br>
            <input type="radio" id="mealprep" name="favorite_image" value="mealprep.gif">
            <label for="mealprep">Meal Prep</label><br><br>
            <input type="submit" value="Set Favorite Image">
        </form>
    </div>
</body>
</html>
