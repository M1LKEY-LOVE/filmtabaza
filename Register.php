<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db = "pozicovna";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$msg = "";
$msg_class = "";

if (isset($_POST["register"])) {
    $meno = $_POST["meno"];
    $priezvisko = $_POST["priezvisko"];
    $email = $_POST["email"];
    $telefon = $_POST["telefon"];
    $heslo = $_POST["heslo"];
    
    $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($check_email) > 0) {
        $msg = "Používateľ s týmto e-mailom už existuje!";
        $msg_class = "alert-danger";
    } else {
        $sql = "INSERT INTO users (meno, priezvisko, heslo, telefon, email) 
                VALUES ('$meno', '$priezvisko', '$heslo', '$telefon', '$email')";
        
        if (mysqli_query($conn, $sql)) {
            $msg = "Registrácia úspešná! Teraz sa môžete prihlásiť.";
            $msg_class = "alert-success";
        } else {
            $msg = "Chyba pri registrácii: " . mysqli_error($conn);
            $msg_class = "alert-danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Registrácia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .register-card { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container">
    <div class="register-card">
        <h3 class="text-center mb-4">Registrácia nového člena</h3>
        
        <?php if ($msg): ?>
            <div class="alert <?php echo $msg_class; ?> text-center"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Meno</label>
                    <input type="text" name="meno" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Priezvisko</label>
                    <input type="text" name="priezvisko" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Telefón</label>
                <input type="text" name="telefon" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Heslo</label>
                <input type="password" name="heslo" class="form-control" required>
            </div>
            <button type="submit" name="register" class="btn btn-primary btn-lg w-100 mb-3">Zaregistrovať sa</button>
            <div class="text-center">
                <a href="index.php" class="text-decoration-none text-muted small">Naspäť na prihlásenie</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>