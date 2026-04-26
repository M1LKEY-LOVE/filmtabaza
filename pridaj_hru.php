<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect("localhost", "root", "root", "pozicovna");

if (!$conn) {
    die("Chyba pripojenia: " . mysqli_connect_error());
}

$nazov = $_POST['nazov'];
$zaner = $_POST['zaner'];
$platforma = $_POST['platforma'];

$sql = "INSERT INTO hry (nazov, zaner, platforma) VALUES ('$nazov', '$zaner', '$platforma')";
mysqli_query($conn, $sql);

header("Location: index.php");
exit();
?>