<?php
$databaza = mysqli_connect("localhost", "root", "root", "pozicovna");

// login
$chybova_sprava = "";
if (isset($_POST["login"])) {
    $pouzivatelske_meno = $_POST["username"];
    $heslo = $_POST["password"];

    $vysledok_uzivatel = mysqli_query($databaza, "SELECT * FROM users WHERE meno = '$pouzivatelske_meno'");
    $uzivatel = mysqli_fetch_assoc($vysledok_uzivatel);

    if ($uzivatel && ($heslo == $uzivatel['heslo'] || password_verify($heslo, $uzivatel['heslo']))) {
        setcookie("logged", "1", time() + 3600);
        setcookie("user_id", $uzivatel['id'], time() + 3600);
        setcookie("user_meno", $uzivatel['meno'], time() + 3600);
        header("Location: index.php");
        exit();
    } else {
        $chybova_sprava = "Nesprávne meno alebo heslo!";
    }
}

// logout
if (isset($_POST["logout"])) {
    setcookie("logged", "", time() - 3600);
    header("Location: index.php");
    exit();
}

// vypožičanie a vracanie hier
if (isset($_COOKIE["logged"]) && isset($_POST['akcia'])) {
    $id_hry = intval($_POST['id']);
    $id_pouzivatela = intval($_COOKIE['user_id']);

    if ($_POST['akcia'] == "vypozicat") {
        mysqli_query($databaza, "UPDATE hry SET stav = 'vypožičané' WHERE id = $id_hry");
        mysqli_query($databaza, "INSERT INTO vypozicky (user_id, hra_id) VALUES ($id_pouzivatela, $id_hry)");
    } 
    elseif ($_POST['akcia'] == "vratit") {
        mysqli_query($databaza, "UPDATE hry SET stav = 'voľné' WHERE id = $id_hry");
        mysqli_query($databaza, "DELETE FROM vypozicky WHERE hra_id = $id_hry");
    }
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Požičovňa Hier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; min-height: 100vh; display: flex; flex-direction: column; }
        .full-center { flex: 1; display: flex; align-items: center; justify-content: center; }
        .main-card { 
            background: white; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            width: 100%;
        }
        .login-card { max-width: 450px; } 
        .table-card { margin-top: 50px; margin-bottom: 50px; }
        .status-free { color: #28a745; font-weight: bold; }
        .status-busy { color: #dc3545; font-weight: bold; }
        .form-control-lg { margin-bottom: 15px; } 
    </style>
</head>
<body>

<div class="container full-center">
    <?php if (!isset($_COOKIE["logged"])): ?>
        
        <div class="main-card login-card">
            <h2 class="text-center mb-4">Prihlásenie</h2>
            
            <?php if ($chybova_sprava): ?>
                <div class="alert alert-danger text-center"><?php echo $chybova_sprava; ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Používateľské meno</label>
                    <input type="text" name="username" class="form-control form-control-lg" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Heslo</label>
                    <input type="password" name="password" class="form-control form-control-lg" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary btn-lg w-100 mb-3">Prihlásiť sa</button>
            </form>

            <div class="d-flex justify-content-between mt-2">
                <a href="register.php" class="text-decoration-none">Registrácia</a>
                <a href="forgot_password.php" class="text-decoration-none small">Zabudnuté heslo?</a>
            </div>
        </div>

    <?php else: ?>

        <div class="main-card table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Katalóg hier</h3>
                <div>
                    <span class="me-3 text-muted">Užívateľ: <strong><?php echo $_COOKIE["user_meno"]; ?></strong></span>
                    <form method="post" style="display:inline;">
                        <button name="logout" class="btn btn-outline-danger btn-sm">Odhlásiť sa</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Názov hry</th>
                            <th>Platforma</th>
                            <th>Stav</th>
                            <th>Vypožičal</th>
                            <th class="text-end">Akcia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_prikaz = "SELECT hry.*, users.meno, users.priezvisko, vypozicky.user_id AS vypozical_id
                                       FROM hry 
                                       LEFT JOIN vypozicky ON hry.id = vypozicky.hra_id
                                       LEFT JOIN users ON vypozicky.user_id = users.id";
                        
                        $vysledok_hry = mysqli_query($databaza, $sql_prikaz);
                        
                        while($hra = mysqli_fetch_assoc($vysledok_hry)): ?>
                            <tr class="align-middle">
                                <td class="text-start"><strong><?php echo $hra['nazov']; ?></strong></td>
                                <td><span class="badge bg-light text-dark border"><?php echo $hra['platforma']; ?></span></td>
                                <td class="<?php echo ($hra['stav'] == 'voľné') ? 'status-free' : 'status-busy'; ?>">
                                    <?php echo $hra['stav']; ?>
                                </td>
                                <td><?php echo $hra['meno'] ? $hra['meno'] : "—"; ?></td>
                                <td class="text-end">
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?php echo $hra['id']; ?>">
                                        <?php if ($hra['stav'] == "voľné"): ?>
                                            <input type="hidden" name="akcia" value="vypozicat">
                                            <button type="submit" class="btn btn-success">Vypožičať</button>
                                        <?php elseif ($hra['vypozical_id'] == $_COOKIE['user_id']): ?>
                                            <input type="hidden" name="akcia" value="vratit">
                                            <button type="submit" class="btn btn-danger">Vrátiť</button>
                                        <?php else: ?>
                                            <span class="text-muted small">Nedostupné</span>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php endif; ?>
</div>

</body>
</html>