<h1><?= e($booking['title']) ?></h1>

<p><strong>ID:</strong> <?= e($booking['id']) ?></p>
<p><strong>Тур:</strong> <?= e($tour['title'] ?? '—') ?></p>
<?php if ($tour): ?>
    <p><strong>Страна:</strong> <?= e($tour['country']) ?></p>
    <p><strong>Цена:</strong> <?= e($tour['price']) ?> ₽</p>
<?php endif; ?>
<p><strong>Статус:</strong> <?= e($statusNames[$booking['status']] ?? $booking['status']) ?></p>

<p><a href="/bookings">← Назад к списку</a></p>