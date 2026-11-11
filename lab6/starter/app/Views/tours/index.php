<h1><?= e($title) ?></h1>

<?php if (count($tours) === 0): ?>
    <p>Туров нет.</p>
<?php else: ?>
    <div class="grid">
        <?php foreach ($tours as $t): ?>
            <div class="card">
                <h3><a href="/tours/<?= e($t['id']) ?>"><?= e($t['title']) ?></a></h3>
                <p><?= e($t['country']) ?> · <?= e($t['price']) ?> ₽</p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
