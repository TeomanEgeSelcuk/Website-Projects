<?php

// lab08.php

// Enable error reporting for debugging (This helps us see if there are any errors in the code)
// ini_set('display_errors', 1);  // This shows errors when developing (you can remove it in production)
// error_reporting(E_ALL);         // This ensures all types of errors are shown

// Set the timezone to New York (This is the time zone the server should follow)
date_default_timezone_set('America/New_York');

// Base directory for background images (We store our images in a folder called "bimages")
$bg_dir = "bimages/";

// Problem 1: Time-based greeting and background image
$hour = date('H'); // Get the current hour of the day

// Determine greeting and background image based on the current hour
// Depending on the time of day, the greeting and background image will change
if ($hour >= 6 && $hour < 12) {
    // Morning (6 AM to 11:59 AM)
    $greeting = "Good morning";
    $bg_image = $bg_dir . "sun2.jpg";  // Set background image to sun2.jpg
    $font_color = "#000000";  // Set font color to black for better visibility
} elseif ($hour >= 12 && $hour < 18) {
    // Afternoon (12 PM to 5:59 PM)
    $greeting = "Good afternoon";
    $bg_image = $bg_dir . "sky.gif";  // Set background image to sky.gif
    $font_color = "#FFFFFF";  // Set font color to white for better visibility
} elseif ($hour >= 18 && $hour < 21) {
    // Evening (6 PM to 8:59 PM)
    $greeting = "Good evening";
    $bg_image = $bg_dir . "spring.gif";  // Set background image to spring.gif
    $font_color = "#FFFFFF";  // Set font color to white
} else {
    // Night (9 PM to 5:59 AM)
    $greeting = "Good night";
    $bg_image = $bg_dir . "night_sky.gif";  // Set background image to night_sky.gif
    $font_color = "#FFFFFF";  // Set font color to white
}

// Problem 2: Multiplication table processing
$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 0;  // Get the number of rows from the form (if provided)
$columns = isset($_GET['columns']) ? intval($_GET['columns']) : 0;  // Get the number of columns from the form (if provided)
$table_error = "";  // Initialize an error message (if there is an invalid input)

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rows'], $_GET['columns'])) {
    // Check if the number of rows and columns is between 3 and 12
    if ($rows < 3 || $rows > 12) {
        $table_error = "Number of rows must be between 3 and 12.";
    } elseif ($columns < 3 || $columns > 12) {
        $table_error = "Number of columns must be between 3 and 12.";
    }
}

// Problem 3: Favorite image selection
if (isset($_POST['favorite_image'])) {
    $favorite_image = $_POST['favorite_image'];  // Get the selected favorite image from the form
    setcookie('favorite_image', $favorite_image, time() + 86400);  // Save the selected favorite image in a cookie (expires in 24 hours)
    header("Location: lab08.php");  // Reload the page to apply the changes
    exit;  // Stop the script execution here to avoid further processing
}

// Check if the favorite image is set (from the cookie), if so, get the value
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
            /* Set the background image dynamically and adjust it to fit the screen */
            background: url('<?php echo htmlspecialchars($bg_image); ?>') no-repeat center center;
            background-size: cover;
            background-color: #333;
            height: 300px;  /* Set the height of the greeting section */
            position: relative;
            color: <?php echo htmlspecialchars($font_color); ?>;  /* Set the font color dynamically based on the time of day */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #greeting h1 {
            font-size: 48px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);  /* Add shadow to the text for better visibility */
        }

        table {
            width: 100%;  /* Make the table fill the screen width */
            border-collapse: collapse;  /* Merge table borders into a single line */
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;  /* Add borders to the table cells */
        }

        th {
            background-color: #4CAF50;  /* Make the table header background green */
            color: white;  /* Set the text color to white for headers */
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;  /* Make even rows have a light gray background */
        }

        tr:nth-child(odd) {
            background-color: #e6e6e6;  /* Make odd rows have a slightly darker gray background */
        }

        #favorite-image {
            position: fixed;
            top: 10px;
            right: 10px;
            opacity: 0.8;
            text-align: center;
        }

        #favorite-image img {
            max-width: 150px;  /* Limit the size of the favorite image */
            height: auto;
        }
    </style>
</head>

<body>
    <!-- Problem 1: Greeting Section -->
    <div id="greeting">
        <h1><?php echo htmlspecialchars($greeting); ?></h1>
    </div>

    <div id="content">
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

        <!-- Display the multiplication table only if the form is filled correctly -->
        <?php if ($_SERVER['REQUEST_METHOD'] === 'GET' && !$table_error && $rows >= 3 && $rows <= 12 && $columns >= 3 && $columns <= 12): ?>
            <h3>Generated Multiplication Table</h3>
            <table>
                <tr>
                    <th></th>
                    <?php for ($j = 1; $j <= $columns; $j++): ?>
                        <th><?php echo $j; ?></th>  <!-- Print column numbers -->
                    <?php endfor; ?>
                </tr>
                <?php for ($i = 1; $i <= $rows; $i++): ?>
                    <tr>
                        <th><?php echo $i; ?></th>  <!-- Print row numbers -->
                        <?php for ($j = 1; $j <= $columns; $j++): ?>
                            <td><?php echo $i * $j; ?></td>  <!-- Print the result of multiplication -->
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        <?php elseif ($table_error): ?>
            <p style="color: red;"><?php echo $table_error; ?></p>  <!-- Display error if input is incorrect -->
        <?php endif; ?>

        <h2>Select Your Favorite Image</h2>

        <!-- Form to allow the user to choose a favorite image -->
        <form method="post" action="lab08.php">
            <input type="radio" name="favorite_image" value="table.gif" required> Table<br>
            <input type="radio" name="favorite_image" value="turkey5.gif"> Turkey 5<br>
            <input type="radio" name="favorite_image" value="turkey3.gif"> Turkey 3<br>
            <input type="radio" name="favorite_image" value="thanks2.gif"> Thanks 2<br>
            <input type="radio" name="favorite_image" value="mealprep.gif"> Meal Prep<br><br>
            <input type="submit" value="Set Favorite Image">
        </form>

        <!-- Display selected favorite image if it's set -->
        <?php if ($favorite_image): ?>
            <div id="favorite-image">
                <img src="images/<?php echo htmlspecialchars($favorite_image); ?>" alt="Favorite Image">
                <p>Current image: <?php echo htmlspecialchars($favorite_image); ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
