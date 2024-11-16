<?php
// lab.php

// Problem 1: Time-based greeting and background image
date_default_timezone_set('America/New_York'); // Set your timezone

$hour = date('H');

if ($hour >= 6 && $hour < 12) {
    $greeting = "Good morning";
    $bg_image = "morning_texture.jpg";
    $font_color = "#000000"; // Black
} elseif ($hour >= 12 && $hour < 18) {
    $greeting = "Good afternoon";
    $bg_image = "afternoon_texture.jpg";
    $font_color = "#FFFFFF"; // White
} elseif ($hour >= 18 && $hour < 21) {
    $greeting = "Good evening";
    $bg_image = "evening_texture.jpg";
    $font_color = "#FFFFFF"; // White
} else {
    $greeting = "Good night";
    $bg_image = "night_texture.jpg";
    $font_color = "#FFFFFF"; // White
}

// Problem 3: Favorite image selection
if (isset($_POST['favorite_image'])) {
    $favorite_image = $_POST['favorite_image'];
    // Set cookie for 24 hours
    setcookie('favorite_image', $favorite_image, time() + 86400); // 86400 seconds = 24 hours
    // Redirect to the same page to reflect the change
    header("Location: lab.php");
    exit;
}

$favorite_image = isset($_COOKIE['favorite_image']) ? $_COOKIE['favorite_image'] : null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lab Assignment</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        #greeting {
            background-image: url('images/<?php echo $bg_image; ?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            height: 300px;
            position: relative;
            color: <?php echo $font_color; ?>;
        }
        #greeting h1 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 48px;
        }
        #favorite-image {
            position: absolute;
            top: 10px;
            right: 10px;
            opacity: 0.8;
            text-align: center;
        }
        #favorite-image img {
            max-width: 150px;
        }
        #favorite-image p {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0 0 0;
        }
        #content {
            padding: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        iframe {
            border: none;
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>
    <!-- Display the selected favorite image -->
    <?php if ($favorite_image): ?>
        <div id="favorite-image">
            <img src="images/<?php echo $favorite_image; ?>" alt="Favorite Image">
            <p>Current image: <?php echo $favorite_image; ?></p>
        </div>
    <?php else: ?>
        <p>Welcome! Please select your favorite image.</p>
    <?php endif; ?>

    <div id="greeting">
        <h1><?php echo $greeting; ?></h1>
    </div>

    <div id="content">
        <!-- Problem 2: Multiplication Table Form -->
        <h2>Multiplication Table Generator</h2>
        <form method="get" action="generate_table.php" target="table_frame">
            <label for="rows">Enter number of rows (3-12): </label>
            <input type="number" id="rows" name="rows" min="3" max="12" required>
            <br>
            <label for="columns">Enter number of columns (3-12): </label>
            <input type="number" id="columns" name="columns" min="3" max="12" required>
            <br>
            <input type="submit" value="Generate Table">
        </form>

        <iframe name="table_frame"></iframe>

        <!-- Problem 3: Favorite Image Selection Form -->
        <h2>Select Your Favorite Image</h2>
        <form method="post" action="lab.php">
            <label>Select your favorite image:</label><br>
            <input type="radio" name="favorite_image" value="table.gif" required> Table<br>
            <input type="radio" name="favorite_image" value="turkey5.gif"> Turkey 5<br>
            <input type="radio" name="favorite_image" value="turkey3.gif"> Turkey 3<br>
            <input type="radio" name="favorite_image" value="thanks2.gif"> Thanks 2<br>
            <input type="radio" name="favorite_image" value="mealprep.gif"> Meal Prep<br>
            <input type="submit" value="Set Favorite Image">
        </form>
    </div>
</body>
</html>
