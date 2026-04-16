<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $conn = mysqli_connect("localhost", "root", "", "filmtabaza");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } 
    
    
    ?>
</body>
</html>