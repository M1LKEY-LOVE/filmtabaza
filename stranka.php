
<?php
$host = "localhost";
$user = "root";
$pass = "root"; 
$db = "pozicovna"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Chyba pripojenia: " . mysqli_connect_error());
}

if (isset($_GET['akcia']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($_GET['akcia'] == "vypozicat") {
        mysqli_query($conn, "UPDATE hry SET stav = 'vypožičané' WHERE id = $id");
        mysqli_query($conn, "INSERT INTO vypozicky (user_id, hra_id) VALUES (1, $id)");
    } 
    
    if ($_GET['akcia'] == "vratit") {
        mysqli_query($conn, "UPDATE hry SET stav = 'voľné' WHERE id = $id");
        mysqli_query($conn, "DELETE FROM vypozicky WHERE hra_id = $id");
    }
    
    header("Location: index.php");
    exit();
}

$sql = "SELECT hry.id, hry.nazov, hry.zaner, hry.platforma, hry.stav, users.meno, users.priezvisko
        FROM hry
        LEFT JOIN vypozicky ON hry.id = vypozicky.hra_id
        LEFT JOIN users ON vypozicky.user_id = users.id";

$vysledok = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Moja školská databáza hier</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        h1 { text-align: center; color: blue; }
        table { width: 90%; margin: 20px auto; border-collapse: collapse; background-color: white; }
        th { background-color: lightgray; padding: 10px; border: 1px solid black; }
        td { padding: 8px; border: 1px solid gray; text-align: center; }
        .volne { color: green; font-weight: bold; }
        .obsadene { color: red; font-weight: bold; }
        a { text-decoration: none; font-weight: bold; color: blue; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <h1>Zoznam hier na vypožičanie</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Názov hry</th>
                <th>Žáner</th>
                <th>Platforma</th>
                <th>Stav</th>
                <th>Kto má požičané</th>
                <th>Akcia</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while($hra = mysqli_fetch_assoc($vysledok)) { 
            ?>
            <tr>
                <td><?php echo $hra['id']; ?></td>
                <td><?php echo $hra['nazov']; ?></td>
                <td><?php echo $hra['zaner']; ?></td>
                <td><?php echo $hra['platforma']; ?></td>
                <td class="<?php echo ($hra['stav'] == 'voľné') ? 'volne' : 'obsadene'; ?>">
                    <?php echo $hra['stav']; ?>
                </td>
                <td>
                    <?php 
                        if($hra['meno'] != NULL) {
                            echo $hra['meno'] . " " . $hra['priezvisko'];
                        } else {
                            echo "---";
                        }
                    ?>
                </td>
                <td>
                    <?php if($hra['stav'] == "voľné") { ?>
                        <a href="index.php?akcia=vypozicat&id=<?php echo $hra['id']; ?>">Vypožičať</a>
                    <?php } else { ?>
                        <a href="index.php?akcia=vratit&id=<?php echo $hra['id']; ?>">Vrátiť</a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>