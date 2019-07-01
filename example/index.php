<?php
/**
 * Пример на основе игры правда или ложь
 */
require_once __DIR__ . '/../kernel/YandexAlisa.php';

$yandexBot = new \bot\kernel\YandexAlisa();
if ($yandexBot->output) {
    $yandexBot->name = 'Game_Truth_or_Action';

    $button = ['Играть', 'Правила']; // Первоначальные кнопки
    $yandexBot->setButtons($button); // Инициализируем кнопки

    $yandexBot->newCommand = __DIR__ . '/param/allCommand.php'; // Добавляем обработчик комманд

    require_once __DIR__ . '/param/exampleCommand.php'; // Подключаем логику навыка
    $yandexBot->processingCommand = new exampleCommand(); // Ваш класс с логикой навыка. Важно! Класс должен быть унаследован от класса Command

    $yandexBot->welcome = include __DIR__ . '/param/welcome.php'; // Массив приветственных фраз
    $yandexBot->help = include __DIR__ . '/param/help.php';       // Массив помощи по навыку

    echo $yandexBot->alisa();

} else {
    echo 'Ok';
}