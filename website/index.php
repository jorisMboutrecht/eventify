<?php
require_once 'config/database.php';

$stmt = $pdo->prepare("
    SELECT
    e.title,
    e.description,
    e.start_date,
    e.event_id,
    l.location_name,
    l.city
    from events e
    join locations l on e.location_id = l.location_id
    where e.status = 'published'
    ORDER BY e.start_date ASC
");

$stmt->execute();
$events = $stmt->fetchALL(PDO::FETCH_ASSOC);
?>

<?php $pageTitle = 'Home'; require __DIR__ . '/includes/header.php'; ?>

    <section class="hero">
        <h1>Welkom bij Eventify.</h1>
        <p>Ontdek bizondere en beleef onvergetelijke momenten.</p>
        <a href="#evenementen" class="button">Ontdek evenementen</a>
    </section>

    <section class="events" id="evenementen">
        <h2>Ontdek evenementen</h2>

        <div class="event-grid">
            <?php if (count($events) > 0): ?>

                <?php foreach ($events as $event): ?>
                    <a href="pages/event.php?id=<?= (int)$event['event_id'] ?>" class="event-card">
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
                            <?= htmlspecialchars($event['start_date'] ?? 'Nog niet bekend') ?>
                        </p>
                    </a>
                <?php endforeach; ?>

            <?php else: ?>
                <p>Er zijn momenteel geen evenementen beschikbaar.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="events" id="over-ons">
        <h2>Over eventify</h2>
        <p>Eventify helpt je om leuke evenementen te ontdekken en nieuwe ervaringen op te doen.</p>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
