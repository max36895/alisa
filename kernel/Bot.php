<?php

namespace bot\kernel;

use bot\components\Text;
use bot\kernel\param\ProcessingCommand;

/**
 * Class BotSite
 * Основной класс обработки и инициализации команд
 *
 * @property \bot\kernel\param\processingCommand $processingCommand   - Обработчик специальных команд для навыка является потомком класса Command (Рекомендуемы параметр)
 *
 * /////////// Текста, которые используются в стандартных командах
 * @property array $welcome - Текст для приветствия пользователя. Тут стоит поприветствовать пользователя, и в крации рассказать что это за навык.
 * (Обязательный параметр)
 *
 * @property array $help - Помощь по навыку. Здесь должно быть подробное описание по тому, как пользоваться навыком
 * (обязательный параметр)
 *
 * @property array $goodName - Текст когда пользователь вводит свое имя. Тут можно вставить что-то своё.
 * (Не обязательный параметр)
 *
 * @property array $about - Информация о Вас или Вашей компании
 * (Не обязательный параметр, если пустой, то используется help)
 *
 * @property array $thank - Текст для благодарности. используется когда пользователь решил поблагодарить.
 * (Не обязательный параметр)
 *
 * @property array $by - Текст для прощания с пользователем.
 * (Не обязательный параметр)
 *
 * @property array $randomText - Текст на непонятный запрос
 * (В редких случаях может запуститься)
 *
 * ////////// Информация о пользователе
 * @property $commandTextFull - Полный текст, который ввел пользователь
 * @property $commandText - Текст, который ввел пользователь, и который был как-то модернизирован.
 * @property $messageId - Идентификатор сообщения пользователя
 * @property $clientKey - Идентификатор пользователя
 *
 * ///////// Отображение результатов
 * @property $textMessage - Текст, для ответа пользователю
 * @property $urlMessage - Ссылка на сайт или какой-либо ресурс. Используеьтся вместе с $buttonMessage
 * @property $buttonMessage - Текст для кнопки. Обычно используется как ссылка на сайт.
 *
 * ///////// Основные параметры ассистента
 * @property string $name - Имя бота
 * @property $url - Адрес сайта
 * @property $isLog - Триггер отвечающий за запись логов.
 * @property $sessionDir - Директория в которой будут храниться сессии. Важно дирректория должна существовать.
 * @property $botParamsJson - Сохранение параметров в json формате
 * @property $param - Параметры используется для обработки новых команд
 * @property string $newCommand - Массив с дополнительными параметрами для обработки.
 * @property $keyCommand - Ключ команды.
 */
class Bot
{
    /**
     * Приветствие, необходимо при заходе пользователя в навык
     * @var array
     */
    public $welcome = [
        'Добрый день!\n',
        'Здравствуйте!\n.',
    ];

    protected function getWelcome(): string
    {
        return Text::getRandText($this->welcome);
    }

    /**
     * Рандомный текс, если навык не может понять что хочет пользователь
     * @var array
     */
    public $randomText = [
        ['Это немного не входит в мои обязанности. Скажите \"Помощь\", чтобы получить информацию по навыку', '', ''],
        ['Это уже вне моей компетенции. Скажите \"Помощь\", чтобы получить информацию по навыку', '', ''],
    ];

    protected function getRandomText(): string
    {
        return Text::getRandText($this->randomText);
    }

    /**
     * Если пользователь сказал имя, то говорит что имя красивое
     * @var array
     */
    public $goodName = [
        'У вас очень красивое имя😍.',
        'Мне нравится ваше имя😻.',
        'Это очень хорошее имя😻.',
        'Вы наверняка также красивы как и ваше имя😍.',
        'Это одно из моих любимых имен😏.',
    ];

    protected function getGoodName(): string
    {
        return Text::getRandText($this->goodName);
    }

    /**
     * Помощь в навигации по навыку (обязательно)
     * @var array
     */
    public $help = [
        'помощь',
    ];

    protected function getHelp(): string
    {
        return Text::getRandText($this->help);
    }

    /**
     * О вас(Не обязательно, если пустое, то используется help)
     * @var array
     */
    public $about = [];

    protected function getAbout(): string
    {
        if (is_string($this->about) || (is_array($this->about) && count($this->about) != 0)) {
            return Text::getRandText($this->about);
        }
        return Text::getRandText($this->help);
    }

    /**
     * Прощание (рекомендуемы параметр)
     * @var array
     */
    public $by = [];

    protected function getBy(): string
    {
        if (is_string($this->by) || (is_array($this->by) && count($this->by) != 0)) {
            return Text::getRandText($this->by);
        }
        return 'Пока, пока\n Всего вам хорошего и успехов во всём 🍀';
    }

    /**
     * Благодарность
     * @var array
     */
    public $thank = [];

    protected function getThank(): string
    {
        if (is_string($this->thank) || (is_array($this->thank) && count($this->thank) != 0)) {
            return Text::getRandText($this->thank);
        }
        return 'И вам большое спасибо, за то что пользуетесь моими навыками 😇\nВсего вам самого доброго 🍀\nС наилучшими пожеланиями Максим ✌';
    }

    /**
     * Директория куда сохранять данные.
     * Изначально сохраняет в дирректорию расположения навыка.
     * @var string
     */
    public $sessionDir = '';

    /**
     * Инициализация переменной, для хренения сессий.
     * рекомендуется вызывать функцияю, так как позволит избежать случаев, когда дирректории нет, а записть файла идет.
     *
     * @param $dir
     *
     * @return bool
     */
    public function setSessionDir($dir): bool
    {
        if (is_dir($dir)) {
            $this->sessionDir = $dir;
            if ($this->processingCommand !== null) {
                $this->processingCommand->sessionDir = $this->sessionDir;
            }
            return true;
        }
        return false;
    }

    public $botParamsJson; // Сохранение параметров в json формате
    protected $textMessage; // Ответ на запрос пользователя
    protected $buttonMessage; // Текст для кнопки
    protected $urlMessage; // Ссылка на сайт или какой-либо ресурс

    protected $param; // Параметры используется для обработки новых команд
    public $name = 'MaximkoBot'; // Имя бота
    public $isLog = true; // Триггер отвечающий за запись логов

    public $commandText; // Текст пользователя
    public $clientKey; // Идентификатор пользователя
    public $commandTextFull; // Полный текс пользователя
    public $messageId; // Идентификатор сообщения пользователя

    public $newCommand = __DIR__ . '/param/allCommand.php'; // Массив с командами для пользователей
    public $processingCommand = null; // Обработчик специальных команд для навыка

    public $url = 'https://alisa.islandgift.ru'; // Адресс сайта

    public $keyCommand = null; // Идентификатор команды

    /**
     * Получить список всех обрабатываемых команд
     *
     * @return array
     */
    public function getAllCommand(): array
    {
        if (is_file($this->newCommand)) {
            if ($this->newCommand !== __DIR__ . '/param/allCommand.php') {
                return array_merge(include $this->newCommand, include __DIR__ . '/param/allCommand.php');
            } else {
                return include $this->newCommand;
            }
        }

        return include __DIR__ . '/param/allCommand.php';
    }

    /**
     * Инициализация обработчика для новых команд
     * Проще говоря инициализируется класс отвечающий за логику навыка и взаимодействия с пользователем
     */
    public function getProcessingCommand(): void
    {
        if ($this->processingCommand === null) {
            require __DIR__ . '/param/ProcessingCommand.php';
            $this->processingCommand = new ProcessingCommand();
        }

        $this->processingCommand->userId = $this->clientKey;
        $this->processingCommand->botName = $this->name;
        $this->processingCommand->sessionDir = $this->sessionDir;
    }

    protected function init(): void
    {
        $this->textMessage = '';
        $this->buttonMessage = '';
        $this->urlMessage = '';
        $this->botParamsJson = 'Нет параметров';
        $this->sessionDir = '';
    }

    /**
     * Разбирает текст пользователя и обрабатывает его
     *
     * @return string
     */
    protected function commandKey(): string
    {
        $key = 'm_null';
        $key = (($this->commandText == '' || $this->commandText == ' ') ? 'help' : $key);

        $allCommands = $this->getAllCommand();

        $undefinedText = $this->processingCommand->undefinedText($this->commandText, 'start', $this->commandTextFull);
        if ($undefinedText === null) {

            foreach ($allCommands as $allCommand) {
                /**
                 * Если у команды ключ равен 1 или -2, тогда происходит точное сравнение, иначе ищется совпадение.
                 */
                if ($allCommand[2] == 1 && $allCommand[1] != -2) {
                    $key = (($this->commandText == $allCommand[0]) ? $allCommand[1] : $key);
                } else {
                    $key = ((strpos($this->commandText, $allCommand[0]) !== false) ? $allCommand[1] : $key);
                }

                /**
                 * Команды с идентификатором command в приоритете
                 * и обрабатываютя при первом нахождении, завершая обход массива с командами.
                 */
                if ($key == 'command') {
                    $this->param = $this->processingCommand->commands($allCommand[2]);
                    $this->keyCommand = $allCommand[2];
                    break;
                }
            }
        } else {
            $this->param = $undefinedText;
            $key = 'command';
        }

        if ($key == 'm_null') {
            $undefinedText = $this->processingCommand->undefinedText($this->commandText, 'end', $this->commandTextFull);
            if ($undefinedText === null) {
                $key = 'help';
            } else {
                $this->param = $undefinedText;
                $key = 'command';
            }
        }
        return $key;
    }

    /**
     * Сохранение пользовательских данных
     */
    protected function saveCommand(): void
    {
        /**
         * Яндекс примерно раз в минуту шлет команду пинг на навык,
         * что бы убедиться что он работает.
         * Поэтому не записываем команду пинг
         **/
        if ($this->commandText != 'ping' && $this->commandText != 'пинг') {
            $param = json_encode($this->botParamsJson, JSON_UNESCAPED_UNICODE);

            if (!is_dir($this->sessionDir . 'session')) {
                mkdir($this->sessionDir . 'session');
            }
            $file = fopen($this->sessionDir . 'session/' . $this->clientKey . '.json', 'w');
            fwrite($file, $param);
            fclose($file);
        }
    }

    /**
     * Обработка стандартных команд, и генерация ответа.
     *
     * @param $key
     *
     * @return void
     */
    public function command($key): void
    {
        switch ($key) {
            case 'command':
                $this->textMessage = $this->param[0];
                $this->buttonMessage = $this->param[1] ?? '';
                $this->urlMessage = $this->param[2] ?? '';
                break;

            case 'hello':
                $this->textMessage = $this->getWelcome();
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case 'test':
                $this->textMessage = 'База, база прием! Мы на связи😊\nКак нас слышно?\nПрием.';
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case 'mat':
                $iText = rand(1, 4);
                if ($iText == 1) {
                    $this->textMessage = 'А вот не стоит обзываться!\nЭто крайне не культурно и не прилично';
                    $this->buttonMessage = 'Буду вежлив';
                    $this->urlMessage = ($this->url . '/1');
                } elseif ($iText == 2) {
                    $this->textMessage = 'Эх\nНе хорошо говорить подобные слова\nКак вам не стыдно поступать подобным образом';
                    $this->buttonMessage = 'Прошу прощения';
                    $this->urlMessage = ($this->url . '/2');
                } elseif ($iText == 3) {
                    $this->textMessage = 'А слабо сказать тоже самое только без нецензурных слов😉';
                    $this->buttonMessage = 'Конечно могу';
                    $this->urlMessage = ($this->url . '/3');
                } else {
                    $this->textMessage = 'Мат?!?😳\n Ой все!!!\nНе дружу я с тобой!';
                    $this->buttonMessage = 'Не ойвсекай';
                    $this->urlMessage = ($this->url . '/1');
                }
                break;

            case 'by':
                $this->textMessage = $this->getBy();
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case 'thank':
                $this->textMessage = $this->getThank();
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case 'ping':
                $this->textMessage = 'Все в порядке я на связи. Как нас слышно? Прием';
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case 'xa-xa':
                $xa_xa = ['Ха ха ха, мне с вами весело 😃', 'Ха ха ха, а вы забавный человек 😂', 'С вами очень приятно общаться, вы супер 😃', 'С вами так весело 😂'];
                $this->textMessage = $xa_xa[rand(0, count($xa_xa) - 1)];
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case 'morning':
                $night = ['И вам Спокойной ночи и крепких снов 😪', 'Добрых снов 🌕', 'Спокойной ночи 🌝', 'Приятных сновидений 😪'];
                $this->textMessage = $night[rand(0, count($night) - 1)];
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case 'about':
                $this->textMessage = $this->getHelp();
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case 'help':
                $this->textMessage = $this->getHelp();
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case 'goodName':
                $this->textMessage = $this->getGoodName();
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case 'token':
                $this->textMessage = $this->clientKey;
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case -1:
                $this->textMessage = ' ';
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            default:
                $this->textMessage = $this->getRandomText();;
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;
        }
    }

    /**
     * Запуск Бота
     *
     * @return string
     */
    public function start(): string
    {
        return $this->commandKey();
    }
}