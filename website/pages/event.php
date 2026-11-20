<?php
require_once '../config/database.php';

$eventId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$eventId) {
    http_response_code(400);
    die('Ongeldig evenement ID.');
}

$stmt = $pdo->prepare("
    SELECT
        e.title,
        e.description,
        e.start_date,
        e.end_date,
        l.location_name,
        l.street,
        l.house_number,
        l.postal_code,
        l.city,
        l.country
    FROM events e
    JOIN locations l ON e.location_id = l.location_id
    WHERE e.event_id = :id AND e.status = 'published'
");
$stmt->execute(['id' => $eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    http_response_code(404);
    die('Evenement niet gevonden.');
}
?>

<?php $pageTitle = $event['title']; require __DIR__ . '/../includes/header.php'; ?>

<section class="events">
    <h2><?= htmlspecialchars($event['title']) ?></h2>

    <p><?= nl2br(htmlspecialchars($event['description'] ?? 'Geen beschrijving beschikbaar.')) ?></p>

    <h3>Datum en Tijd</h3>
    <p>
        <?php if ($event['start_date']) : ?>
            Start: <?= htmlspecialchars(date('d-m-Y H:i', strtotime($event['start_date']))) ?>
        <?php else: ?>
            Startdatum nog niet bekend
        <?php endif; ?>
    </p>

    <?php if ($event['end_date']): ?>
        <p>
            Einde: <?= htmlspecialchars(date('d-m-Y h:i', strtotime($event['end_date']))) ?>
        </p>
    <?php endif; ?>

    <h3>Locatie</h3>
    <p>
        <?= htmlspecialchars($event['location_name']) ?> <br>
        <?= htmlspecialchars($event['street']) ?> <br>
        <?= htmlspecialchars($event['house_number']) ?> <br>
        <?= htmlspecialchars($event['postal_code']) ?> <br>
        <?= htmlspecialchars($event['city']) ?> <br>
        <?= htmlspecialchars($event['country']) ?> <br>
    </p>

    <a href="events.php" class="button">Terug naar evenementen</a>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
