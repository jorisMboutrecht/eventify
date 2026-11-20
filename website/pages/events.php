
<?php
require_once "../config/database.php";

$stmt = $pdo->prepare("
    SELECT
        e.title,
        e.description,
        e.start_date,
        e.event_id,
        l.location_name,
        l.city
    FROM events e
    JOIN locations l ON e.location_id = l.location_id
    WHERE e.status = 'published'
    ORDER BY e.start_date ASC
");

$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php $pageTitle = 'Evenementen'; require __DIR__ . '/../includes/header.php'; ?>

<section class="events" >
    <h2>Alle evenementen</h2>
    <p>Ontdek wat er binnenkort te beleven is!</p>

    <div class="event-grid">
        <?php if (count($events) > 0): ?>

            <?php foreach ($events as $event): ?>
                <a href="event.php?id=<?= (int)$event['event_id'] ?>" class="event-card">
                    <h3><?= htmlspecialchars($event['title']) ?></h3>

                    <P>
                        <?= htmlspecialchars($event['description']) ?>
                    </p>

                    <p>
                        <strong>Locatie:</strong>
                        <?=htmlspecialchars($event['location_name']) ?>, 
                        <?= htmlspecialchars($event['city']) ?>
                    </p>

                    <p>
                        <strong>Datum:</strong>
                        <?php
                        if ($event['start_date']) {
                            echo htmlspecialchars(
                                date('d-m-Y H:i', strtotime($event['start_date']))
                            );
                            } else {
                                echo "nog niet bekend";
                            }
                        ?>
                    </p>

                </a>
            <?php endforeach; ?>

        <?php else: ?>
            <p>Er zijn momenteel geen evenementen beschikbaar.</p>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
