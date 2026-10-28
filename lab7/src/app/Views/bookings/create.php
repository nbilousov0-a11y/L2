<h1><?= e($title) ?></h1>

<form method="POST" action="/bookings">
    <label>
        Название:
        <input type="text" name="title" value="<?= e($old['title'] ?? '') ?>">
        <?php if (isset($errors['title'])): ?>
            <span class="error"><?= e($errors['title']) ?></span>
        <?php endif; ?>
    </label>

    <label>
        Тур:
        <select name="tour_id">
            <option value="">— выберите —</option>
            <?php foreach ($tours as $t): ?>
                <option value="<?= e($t['id']) ?>"
                    <?= ($old['tour_id'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                    <?= e($t['title']) ?> (<?= e($t['price']) ?> ₽)
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['tour_id'])): ?>
            <span class="error"><?= e($errors['tour_id']) ?></span>
        <?php endif; ?>
    </label>

    <label>
        Статус:
        <select name="status">
            <?php foreach ($statusNames as $key => $name): ?>
                <option value="<?= e($key) ?>"
                    <?= ($old['status'] ?? 'new') === $key ? 'selected' : '' ?>>
                    <?= e($name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <button type="submit">Создать бронирование</button>
</form>