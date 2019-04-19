# Работа с картинками и карточками
Вся логика навыка должна находится в классе унаследованном от Command. Иначе будет брошено исключение и навык не заработает!

# Работа с картинками
Работа с картинками осуществляется в классе где вы пишите логику.
## Карточка
```php
    $this->image->isBigImage = true; // Указываем что используем карточку
    $this->image->title = 'Title'; // Заполяем заголовок для карточки
    $this->image->description = 'Description'; // Заполяем описание для карточки
    $this->image->button = ['text' => 'button', 'url' => 'https://www.islandgift.ru', 'payload' => 'payload']; // Указываем кнопку, если необходимо. 
```
## Список с картинками
```php
   $this->image->isItemsList = true; // Указываем что использовать список
   $this->image->title = 'Title'; // Заполняем заголовок для списка
   $button = ['text' => 'button', 'url' => 'https://www.islandgift.ru', 'payload' => 'payload']; // Создаем кнопку
   $this->image->addImages('imgDir', 'Title', 'Description', $button); // Добавляем картинки 
   $this->image->addImages('imgDir', 'Title', 'Description', null);    //===================
   $this->image->footerText = 'Footer'; // Заполняем поле footer если необходимо
   $this->image->footerButton = $button; // казываем кнопку для footera
```
## Список без картинок
```php
   $this->image->isItemsList = true; // Указываем что использовать список
   $this->image->title = 'Title'; // Заполняем заголовок для списка
   $button = ['text' => 'button', 'url' => 'https://www.islandgift.ru', 'payload' => 'payload']; // Создаем кнопку
   $this->image->addImages('', 'Title', 'Description', $button); // Добавляем картинки 
   $this->image->addImages('', 'Title', 'Description', null);    //===================
   $this->image->isItemsImage = false; // Указываем, что не нужно отображать картинки
   $this->image->footerText = 'Footer'; // Заполняем поле footer если необходимо
   $this->image->footerButton = $button; // казываем кнопку для footer`a
```

# Работа со звуками
Работа со звуками осуществляется дополнение функции getSound
А именно в параметр $customParams передается массив вида:
```php
    [
        ['key' => '#$win$#', 'sounds' => ['<speaker audio=\"alice-sounds-game-win-1.opus\">', '<speaker audio=\"alice-sounds-game-win-2.opus\">', '<speaker audio=\"alice-sounds-game-win-3.opus\">',]],
    ]
```
Помимо этого есть стандартные звуки.
Чтобы воспроизвести звук в навыке, необходимо в текст прописать key, необходимого звука.

# Работа с nlu
Для работы с nlu предусмотрена переменная:
```php
$this->nlu
```
Это экземпляр класса api/AlisaNlu.php.
Подробнее описано в классе.