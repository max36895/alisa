<?php
require_once __DIR__ . '/../YandexBot.php';

$yandexBot = new \alisa\bot\YandexBot();
if ($yandexBot->output) {
    $yandexBot->name = 'Game_Truth_or_Action';

    $button = ['Играть', 'Правила']; // Первоначальные кнопки
    $yandexBot->setButtons($button); // Инициализируем кнопки

    $yandexBot->dirAllCommand = __DIR__ . '/param/allCommand.php'; // Добавляем обработчик комманд

    require_once __DIR__ . '/param/newCommand.php'; // Подключаем логику навыка
    $yandexBot->newCommand = new newCommand(); // Ваш класс с логикой навыка. Важно! Класс должен быть унаследован от класса Command

    $yandexBot->welcome = include __DIR__ . '/param/welcome.php'; // Массив приветственных фраз
    $yandexBot->help = include __DIR__ . '/param/help.php';       // Массив помощи по навыку

    echo $yandexBot->alisa();

} else {
    echo 'Ok';
}