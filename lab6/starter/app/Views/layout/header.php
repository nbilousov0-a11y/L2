<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= e($title ?? config('name')) ?> — <?= e(config('name')) ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header>
    <h1><?= e(config('name')) ?></h1>
    <nav>
        <a href="/">Главная</a>
        <a href="/bookings">Бронирования</a>
        <a href="/tours">Туры</a>
        <a href="/bookings/create">+ Новое бронирование</a>
    </nav>
</header>
<main>