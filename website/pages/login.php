<?php

session_start();
require_once __DIR__ . '/../config/database.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $search = $pdo->prepare("SELECT user_id, username, email, password FROM users
    where email = :email");
    $search->execute([
        "email" => $email,
    ]);
    $search->fetch(PDO::FETCH_ASSOC)
}

?>
<?php
$pageTitle = 'Inloggen';
require __DIR__ . '/../includes/header.php';
?>

<main class="login-section">
    <h1>Inloggen</h1>
    <p>De inlogpagina is nog niet beschikbaar.</p>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
