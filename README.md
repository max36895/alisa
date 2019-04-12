#Структура
```$xslt
api     - Дополнительные инструменты для бота 
param   - В данной папке находятся параметры для бота и класс Command
example - Пример бота.
BotSite.php     - Непосредственно сам бот. Обрабатывает и понимает все команды пользователя.
YandexBot.php   - Яндекс Алиса
```

#Запуск
Для запуска необходим следующий минимальный код
```$xslt
require_once __DIR__ . '/bot/YandexBot.php'; // Полный путь к боту

$yandexBot = new YandexBot(); // создаем объект класса
if ($yandexBot->output) { // проверяем параметры
    $yandexBot->name = 'newBot'; // Даем имя боту (используется для логов)

    $button = ['играть']; // Кнопки
    $yandexBot->setButtons($button); // Инициализация кнопки

    $yandexBot->dirAllCommand = __DIR__ . '/param/allCommand.php'; // Путь к своим командам, которые должны обрабатываться

    require_once __DIR__ . '/param/newCommand.php'; // Класс, который обрабатывает новые команды
    $yandexBot->newCommand = new newCommand(); // Инициализируем класс

    $yandexBot->welcome = include __DIR__ . '/param/welcome.php'; // Переопределение стандартного приветствия
    $yandexBot->help = include __DIR__ . '/param/help.php'; // Переопределение стандартного текста помощи

    $yandexBot->addButton($button); // Отображаем кнопки при запуске навыка

    echo $yandexBot->alisa(); // Запускаем навык

} else {
    echo 'Ok';
}
```