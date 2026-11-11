<h1><?= e($title) ?></h1>

<div class="grid">
    <div class="card">
        <div class="card-number"><?= e($total) ?></div>
        <div>Всего бронирований</div>
    </div>
    <div class="card">
        <div class="card-number"><?= e($byStatus['new']) ?></div>
        <div>Новых</div>
    </div>
    <div class="card">
        <div class="card-number"><?= e($byStatus['confirmed']) ?></div>
        <div>Подтверждено</div>
    </div>
    <div class="card">
        <div class="card-number"><?= e($byStatus['paid']) ?></div>
        <div>Оплачено</div>
    </div>
</div>

<p>В системе <?= e($toursCount) ?> туров. Перейдите к
    <a href="/bookings">списку бронирований</a> или
    <a href="/tours">турам</a>.
</p>