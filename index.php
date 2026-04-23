<?php
$host = "localhost";
$user = "root";
$pass = "root"; 
$db = "pozicovna";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error_msg = "";
if (isset($_POST["login"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = $_POST["password"]; 

    $sql = "SELECT * FROM users WHERE meno = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['heslo']) || $password == $row['heslo']) {
            setcookie("logged", "1", time() + 3600);
            setcookie("user_id", $row['id'], time() + 3600); 
            setcookie("user_meno", $row['meno'], time() + 3600);
            header("Location: index.php");
            exit();
        } else {
            $error_msg = "Nesprávne heslo!";
        }
    } else {
        $error_msg = "Používateľ neexistuje!";
    }
}

if (isset($_POST["logout"])) {
    setcookie("logged", "", time() - 3600);
    setcookie("user_id", "", time() - 3600);
    setcookie("user_meno", "", time() - 3600);
    header("Location: index.php");
    exit();
}

if (isset($_COOKIE["logged"]) && isset($_GET['akcia']) && isset($_GET['id'])) {
    $id_hry = intval($_GET['id']);
    $prihlaseny_user_id = intval($_COOKIE['user_id']);

    if ($_GET['akcia'] == "vypozicat") {
        mysqli_query($conn, "UPDATE hry SET stav = 'vypožičané' WHERE id = $id_hry");
        mysqli_query($conn, "INSERT INTO vypozicky (user_id, hra_id) VALUES ($prihlaseny_user_id, $id_hry)");
    } 
    elseif ($_GET['akcia'] == "vratit") {
        $check_sql = "SELECT user_id FROM vypozicky WHERE hra_id = $id_hry";
        $check_res = mysqli_query($conn, $check_sql);
        $vypozicka = mysqli_fetch_assoc($check_res);

        if ($vypozicka && $vypozicka['user_id'] == $prihlaseny_user_id) {
            mysqli_query($conn, "UPDATE hry SET stav = 'voľné' WHERE id = $id_hry");
            mysqli_query($conn, "DELETE FROM vypozicky WHERE hra_id = $id_hry");
        } else {
            header("Location: index.php?error=1");
            exit();
        }
    }
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Požičovňa hier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .volne { color: green; font-weight: bold; }
        .vypozicane { color: red; font-weight: bold; }
        .table-container { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .platform-badge { background-color: #e9ecef; color: #495057; padding: 3px 8px; border-radius: 5px; font-size: 0.85em; font-weight: bold; }
        .login-footer { margin-top: 15px; text-align: center; font-size: 0.9em; }
        .login-footer a { text-decoration: none; color: #6c757d; }
        .login-footer a:hover { color: #0d6efd; }
    </style>
</head>
<body>
<div class="container">
    <?php if (!isset($_COOKIE["logged"])): ?>
        <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="card p-4 shadow-sm" style="width: 380px; border-radius: 15px;">
                <h3 class="text-center mb-4">Prihlásenie</h3>
                <?php if ($error_msg) echo "<div class='alert alert-danger py-2 text-center'>$error_msg</div>"; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Používateľské meno</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Heslo</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">Prihlásiť sa</button>
                </form>
                <div class="login-footer">
                    <a href="register.php">Zaregistrovať sa</a>
                    <span class="text-muted mx-2">|</span>
                    <a href="forgot_password.php">Zabudol som heslo</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="table-container shadow-sm">
            <?php if(isset($_GET['error'])) echo "<div class='alert alert-warning'>Túto hru nemôžeš vrátiť!</div>"; ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="m-0">Katalóg hier</h2>
                <div>
                    <span class="me-3">Prihlásený: <strong><?php echo $_COOKIE["user_meno"]; ?></strong></span>
                    <form method="post" style="display: inline;">
                        <button type="submit" name="logout" class="btn btn-sm btn-danger">Odhlásiť sa</button>
                    </form>
                </div>
            </div>
            <table class="table table-hover text-center">
                <thead class="table-light">
                    <tr>
                        <th>Názov hry</th>
                        <th>Žáner</th>
                        <th>Platforma</th>
                        <th>Stav</th>
                        <th>Vypožičal</th>
                        <th>Akcia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT hry.*, users.meno, users.priezvisko, vypozicky.user_id AS vypozical_id
                            FROM hry 
                            LEFT JOIN vypozicky ON hry.id = vypozicky.hra_id
                            LEFT JOIN users ON vypozicky.user_id = users.id";
                    $vysledok = mysqli_query($conn, $sql);
                    $moje_id = intval($_COOKIE['user_id']);
                    while($h = mysqli_fetch_assoc($vysledok)) {
                    ?>
                    <tr class="align-middle">
                        <td class="text-start"><strong><?php echo $h['nazov']; ?></strong></td>
                        <td><?php echo $h['zaner']; ?></td>
                        <td><span class="platform-badge"><?php echo $h['platforma']; ?></span></td>
                        <td class="<?php echo ($h['stav'] == 'voľné') ? 'volne' : 'vypozicane'; ?>">
                            <?php echo $h['stav']; ?>
                        </td>
                        <td><?php echo $h['meno'] ? ($h['meno'] . " " . $h['priezvisko']) : "—"; ?></td>
                        <td>
                            <?php if ($h['stav'] == "voľné"): ?>
                                <a href="index.php?akcia=vypozicat&id=<?php echo $h['id']; ?>" class="btn btn-sm btn-outline-success">Vypožičať</a>
                            <?php else: ?>
                                <?php if ($h['vypozical_id'] == $moje_id): ?>
                                    <a href="index.php?akcia=vratit&id=<?php echo $h['id']; ?>" class="btn btn-sm btn-danger">Vrátiť</a>
                                <?php else: ?>
                                    <span class="text-muted small">Obsadené</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>