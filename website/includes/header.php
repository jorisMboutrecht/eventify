<?php
$basePath = str_contains($_SERVER['SCRIPT_NAME'] ?? '', '/pages/') ? '../' : '';
$pageTitle = $pageTitle ?? 'Eventify';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Eventify</title>
    <link rel="stylesheet" href="<?= $basePath ?>assets/style/style.css">
</head>
<body>
    <nav class="navbar">
        <a class="logo" href="<?= $basePath ?>index.php">&#10022; Eventify</a>
        <ul class="nav-links">
            <li><a href="<?= $basePath ?>index.php">Home</a></li>
            <li><a href="<?= $basePath ?>pages/events.php">Evenementen</a></li>
            <li><a href="<?= $basePath ?>index.php#over-ons">Over ons</a></li>
            <li><a href="<?= $basePath ?>pages/login.php">Inloggen</a></li>
        </ul>
    </nav>
