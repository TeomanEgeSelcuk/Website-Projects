<?php
// generate_table.php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Multiplication Table</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
            background-color: #f2f2f2;
        }
        tr:nth-child(even) td {
            background-color: #e6e6e6;
        }
    </style>
</head>
<body>
<?php
$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 0;
$columns = isset($_GET['columns']) ? intval($_GET['columns']) : 0;

$errors = array();

if ($rows < 3 || $rows > 12) {
    $errors[] = "Number of rows must be between 3 and 12.";
}

if ($columns < 3 || $columns > 12) {
    $errors[] = "Number of columns must be between 3 and 12.";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
    echo "<p><a href='javascript:history.back()'>Go back</a> and enter valid numbers.</p>";
    exit;
}

// Generate multiplication table
echo '<table>';
for ($i = 1; $i <= $rows; $i++) {
    echo '<tr>';
    for ($j = 1; $j <= $columns; $j++) {
        echo '<td>' . ($i * $j) . '</td>';
    }
    echo '</tr>';
}
echo '</table>';
?>
</body>
</html>
