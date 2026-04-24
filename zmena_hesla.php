<?php
$databaza = mysqli_connect("localhost", "root", "root", "pozicovna");

$sprava = "";
$typ_spravy = ""; 

// Zmena hesla
if (isset($_POST["zmenit_heslo"])) {
    $meno_uzivatela = $_POST["username"];
    $nove_heslo = $_POST["new_password"];
    $potvrdenie_hesla = $_POST["confirm_password"];

    if ($nove_heslo !== $potvrdenie_hesla) {
        $sprava = "Heslá sa nezhodujú!";
        $typ_spravy = "nezhoda";
    } else {
        // Kontrola existencie pouzivatela
        $kontrola_vysledok = mysqli_query($databaza, "SELECT id FROM users WHERE meno = '$meno_uzivatela'");
        
        if (mysqli_num_rows($kontrola_vysledok) > 0) {
            
            $prikaz_update = "UPDATE users SET heslo = '$nove_heslo' WHERE meno = '$meno_uzivatela'";
            
            if (mysqli_query($databaza, $prikaz_update)) {
                $sprava = "Heslo bolo úspešne zmenené.";
                $typ_spravy = "úspech";
            } else {
                $sprava = "Chyba databázy.";
                $typ_spravy = "nezhoda";
            }
        } else {
            $sprava = "Používateľ neexistuje.";
            $typ_spravy = "nezhoda";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Zmena hesla</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .main-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
    </style>
</head>
<body>

<div class="main-card text-center">
    <h2 class="mb-4">Zmena hesla</h2>

    <?php if ($sprava): ?>
        <div class="alert alert-<?php echo $typ_spravy; ?>"><?php echo $sprava; ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="username" class="form-control mb-3" placeholder="Užívateľské meno" required>
        <input type="password" name="new_password" class="form-control mb-3" placeholder="Nové heslo" required>
        <input type="password" name="confirm_password" class="form-control mb-4" placeholder="Zopakujte heslo" required>
        
        <button type="submit" name="zmenit_heslo" class="btn btn-primary w-100 mb-3">Zmeniť heslo</button>
    </form>

    <a href="index.php" class="text-decoration-none small">Späť na prihlásenie</a>
</div>

</body>
</html>