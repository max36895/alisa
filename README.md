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
```php
require_once __DIR__ . '/bot/YandexBot.php'; // Полный путь к боту

$yandexBot = new YandexBot(); // создаем объект класса
if ($yandexBot->output) { // проверяем параметры
    $yandexBot->name = 'newBot'; // Даем имя боту (используется для логов)

    $button = ['играть']; // Кнопки
    $yandexBot->setButtons($button); // Инициализация кнопки

    $yandexBot->dirAllCommand = __DIR__ . '/param/allCommand.php'; // Путь к своим командам, которые должны обрабатываться

    require_once __DIR__ . '/param/newCommand.php'; // Класс, который обрабатывает новые команды
    $yandexBot->newCommand = new newCommand(); // Инициализируем класс

    $yandexBot->welcome = [
            'Текст для приветствия',
        ]; // Обязательно должно быть инициализированно. Данные сообщения получает пользователь при заходе в навык, а так же при приветствии пользователя.
        $yandexBot->help = [
            'Помощь при работе с навыком',
        ]; // Обязательнл должно быть инициализированно. Так как именно из этого текста пользователь понимает что делает навык. Так же необходимо чтобы пройти модерацию.

    echo $yandexBot->alisa(); // Запускаем навык

} else {
    echo 'Ok';
}
```


#Навыки
Вся логика навыков находится в классах `BotSite` и `YandexBot`
##BotSite
Основной класс, отвечающий за взаимодействие навыка. 
Именно в данном классе происходит поиск команд, а так же обработка стандартных команд, и новых запрограммированных команд.
###Описание переменных
- `information` - Массив с различными интересными фактами
- `welcome` - Массив фраз для приветствия пользователь(Обязательный параметр)
- `params` - Параметры используются для обработки новых команд
- `randomText` - Массив фраз, когда навык совершенно не понял что от него хотят
- `goodName` - Массив фраз, где навык говорит что у пользователя красивое имя
- `help` - Массив фраз для помощь (Обязательный параметр)
- `about` - Массив фраз рассказывающий о вас (Если не заполнен, то берется значение из `help`)
- `by` - Массив фраз для прощания с пользователем
- `botParamsJson` - Данные пользователя в формате json
- `name` - Имя навыка
- `isLog` - Тригер для записи логов
- `isVk` - Проверка что это бот для ВК
- `commandText` - Запрос пользователя
- `clientKey` - Идентификатор пользователя
- `commandTextFull` - Полный запрос пользователя
- `messageId` - Порядковый номер сообщения
- `dirAllCommand` - Путь до массива с обработкой дополнительных команд в навыке
- `newCommand` - Класс отвечающий за логику обработки новых команд
- `url` - Адрес сайта
- `keyCommand` - Ключ команды(не используется)

##YandexBot
Класс унаследованный от `BotSite`.
Отвечает непосредствено за инициализацию параметров, а также за отображение результата.
###Описание переменных

- `output` - Полученый запрос
- `sessionId` - Идентификатор сессии
- `skillId` - Идентификатор навыка
- `userId` - Идентификатор пользователя
- `meta` - Мета информация пользователя
- `nlu` - Полученый nlu