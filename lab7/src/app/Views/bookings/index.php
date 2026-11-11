<h1><?= e($title) ?></h1>

<form method="GET" action="/bookings" style="margin-bottom:20px">
    <label>
        Статус:
        <select name="status" onchange="this.form.submit()">
            <option value="">Все</option>
            <?php foreach ($statusNames as $key => $name): ?>
                <option value="<?= e($key) ?>" <?= $status === $key ? 'selected' : '' ?>>
                    <?= e($name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</form>

<?php if (count($bookings) === 0): ?>
    <p>Бронирований нет.</p>
<?php else: ?>
    <table>
        <thead>
            <tr><th>ID</th><th>Название</th><th>Тур</th><th>Статус</th><th></th></tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $b): ?>
            <?php
                $tour = null;
                foreach ($tours as $t) {
                    if ((int) $t['id'] === (int) $b['tour_id']) { $tour = $t; break; }
                }
            ?>
            <tr>
                <td><?= e($b['id']) ?></td>
                <td><?= e($b['title']) ?></td>
                <td><?= e($tour['title'] ?? '—') ?></td>
                <td><?= e($statusNames[$b['status']] ?? $b['status']) ?></td>
                <td><a href="/bookings/<?= e($b['id']) ?>">Открыть</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>