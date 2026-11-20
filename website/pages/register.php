<?php
require_once __DIR__ . '/../config/database.php';

$error = '';
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if ($username ===  "" || $email === "" || $password === "") {
        $error = "Vul alle velden in.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Vul een geldig e-mailadres in.";
    } elseif (strlen($password) < 8) {
        $error = "Je wachtwoord moet minimaal 8 tekens bevatten.";
    } elseif ($password !== $confirmPassword){
        $error = "De wachtwoorden komen niet overeen.";
    } else {

        $check = $pdo->prepare("
        SELECT user_id
        FROM users
        WHERE username = :username OR email = :email
        ");
        $check->execute([
            "username" => $username,
            "email" => $email
        ]);

        if ($check->fetch()){
            $error = "Deze gebruikers naam of email adress bestaat al.";
        } else {
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("
            INSERT INTO users (username, email, password, created_at)
            VALUES (:username, :email, :password, NOW())
            ");
            $insert->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashPassword
            ]);

            header("Location: login.php?registered=1");
            exit;
        }
    }
}
?>

<?php $pageTitle = 'Registreren'; require __DIR__ . '/../includes/header.php'; ?>

    <section class="register-section">

        <div class="register-box">

            <h2>Account aanmaken</h2>
            <p>wordt lid van Eventify en ontdek leuke evenementen!</p>

            <?php if ($error !== ""): ?>
                <p class="error_message">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <form action="register.php" method="post">
                <label for="username">Gebruikersnaam</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($username) ?>" required>

                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            
                <label for="password">Wachtwoord</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    required
                >

                <label for="confirm_password">Herhaal wachtwoord</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    minlength="8"
                    required
                >

                <button type="submit" class="button">
                    Registreren
                </button>

            </form>

            <p class="login-link">
                Heb je al een account?
                <a href="login.php">Log hier in</a>
            </p>

        </div>

    </section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
