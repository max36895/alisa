<?php
/**
 * User: MaxM18
 */

namespace alisa;

use alisa\param\newCommand;

/**
 * Class BotSite
 * @property \standard\newCommand $newCommand   - Обработчик специальных команд для навыка является потомком класса Command (Рекомендуемы параметр)
 * @property array $information  - Различные интересные факты
 * @property array $randomText   - Текст на непонятный запрос (Не используется)
 * @property $commandTextFull    - Полный текс команды пользователя
 * @property array $goodName     - Текст красивое имя (Не обязательный параметр)
 * @property $dirAllCommand      - Массив с командами пользователей
 * @property $buttonMessage      - Текст дла кнопки
 * @property $botParamsJson      - Сохранение параметровв json формате
 * @property array $welcome      - Текст приветствия (Обязательный параметр)
 * @property $imageTrigger       - Триггер для отображения картинок
 * @property array $params       - Дополнительные команды (не используется)
 * @property $rememberDir        - Дирректория для сохранения пользовательских данных
 * @property $commandText        - Текст команды пользователя
 * @property array $about        - Информация (не обязательный параметр, если пустой, то используется help)
 * @property $textMessage        - Ответ на команду пользователя
 * @property array $thank        - Текст для благодарности(Не обязательный параметр)
 * @property array $help         - Помощь по навыку (обязательный параметр)
 * @property $urlMessage         - Ссылка на сайт или какой-либо ресурс
 * @property $messageId          - Идентификатор сообщения пользователя
 * @property $clientKey          - Идентификатор пользователя
 * @property array $by           - Текст прощания (Не обязательный параметр)
 * @property $param              - Параметры используется для обработки новых команд
 * @property $isLog              - Триггер отвечающий за запись логов
 * @property $name               - Имя бота
 * @property $isVk               - Проверка что это бот для вк
 * @property $url                - адрес сайта
 *
 * @property $keyCommand         - Ключ команда навыка.
 */
class BotSite
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
        return $this->welcome[rand(0, count($this->welcome) - 1)];
    }

    /**
     * Дополнительные параметры (не используюся)
     * @var array
     */
    public $params = [];

    protected function getParam(): string
    {
        return $this->params[rand(0, count($this->params) - 1)];
    }

    /**
     * Рандомный текс, если навык не может понять что хочет пользователь
     * @var array
     */
    public $randomText = [
        ['Это немного не входит в мои обязанности. Скажите \"Помощь\", чтобы получить информацию по навыку', '', ''],
        ['Это уже вне моей компетенции. Скажите \"Помощь\", чтобы получить информацию по навыку', '', ''],
    ];

    protected function getRandomText(): void
    {
        $this->param = $this->randomText[rand(0, count($this->randomText) - 1)];
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
        return $this->goodName[rand(0, count($this->goodName) - 1)];
    }

    /**
     * Помощь в навигации по навыку (обязательно)
     * @var array
     */
    public $help = [
        'Помощь',
    ];

    protected function getHelp(): string
    {
        return $this->help[rand(0, count($this->help) - 1)];
    }

    /**
     * О вас(Не обязательно, если пустое, то используется help)
     * @var array
     */
    public $about = [];

    protected function getAbout(): string
    {
        if (count($this->about) == 0) {
            return $this->help[rand(0, count($this->help) - 1)];
        }
        return $this->about[rand(0, count($this->about) - 1)];
    }

    /**
     * Прощание (рекомендуемы параметр)
     * @var array
     */
    public $by = [];

    protected function getBy(): string
    {
        $count = count($this->by);
        if ($count == 0) {
            return 'Пока, пока\n Всего вам хорошего и успехов во всём🍀';
        }
        return $this->by[rand(0, $count - 1)];
    }

    /**
     * Благодарность
     * @var array
     */
    public $thank = [];

    protected function getThank(): string
    {
        $count = count($this->thank);
        if ($count == 0) {
            return 'И вам большое спасибо, за то что пользуетесь нашими услугами😇\nВсего вам самого доброго🍀';
        }
        return $this->thank[rand(0, $count - 1)];
    }

    public $botParamsJson; // Сохранение параметров в json формате
    protected $textMessage; // Ответ на запрос пользователя
    protected $buttonMessage; // Текст для кнопки
    protected $urlMessage; // Ссылка на сайт или какой-либо ресурс

    protected $param; // Параметры используется для обработки новых команд
    public $name = 'MaximkoBot'; // Имя бота
    public $isLog = true; // Триггер отвечающий за запись логов
    public $isVk = false; // Проверка что это бот для вк

    public $commandText; // Текст пользователя
    public $clientKey; // Идентификатор пользователя
    public $commandTextFull; // Полный текс пользователя
    public $messageId; // Идентификатор сообщения пользователя

    public $dirAllCommand = __DIR__ . '/param/allCommand.php'; // Массив с командами для пользователей
    public $newCommand = null; // Обработчик специальных команд для навыка

    public $url = 'https://www.islandgift.ru';

    public $keyCommand = null;

    /**
     * Инициализация всех команд
     *
     * @return array
     */
    public function getDirAllCommand(): array
    {
        if (is_file($this->dirAllCommand)) {
            if ($this->dirAllCommand !== __DIR__ . '/param/allCommand.php') {
                return array_merge(include $this->dirAllCommand, include __DIR__ . '/param/allCommand.php');
            } else {
                return include $this->dirAllCommand;
            }
        }

        return include __DIR__ . '/param/allCommand.php';
    }

    /**
     * Инициализация обработчика для новых команд
     */
    public function getNewCommand(): void
    {
        if ($this->newCommand === null) {
            require __DIR__ . '/param/newCommand.php';
            $this->newCommand = new newCommand();
        }

        $this->newCommand->userId = $this->clientKey;
        $this->newCommand->botName = $this->name;
    }

    protected function init(): void
    {
        $this->textMessage = '';
        $this->buttonMessage = '';
        $this->urlMessage = '';
        $this->botParamsJson = 'Нет параметров';
    }

    /**
     * Разбирает текст пользователя и обрабатывает его
     *
     * @return string
     */
    protected function commandKey(): string
    {
        $key = 'null';
        $key = (($this->commandText == '' || $this->commandText == ' ') ? 'help' : $key);

        $allCommand = $this->getDirAllCommand();
        $countCommand = count($allCommand);

        $undefinedText = $this->newCommand->undefinedText($this->commandText, '', $this->commandTextFull);
        if ($undefinedText === null) {
            for ($i = 0; $i < $countCommand; $i++) {

                if ($allCommand[$i][2] == 1 && $allCommand[$i][1] != -2) {
                    $key = (($this->commandText == $allCommand[$i][0]) ? $allCommand[$i][1] : $key);
                } else {
                    $key = ((strpos($this->commandText, $allCommand[$i][0]) !== false) ? $allCommand[$i][1] : $key);
                }

                /**
                 * Команды с идентификатором command в приоритете
                 * и обрабатываютя при первом нахождении, завершая обход массива с командами.
                 */
                if ($key == 'command') {
                    $this->param = $this->newCommand->commands($allCommand[$i][2]);
                    $this->keyCommand = $allCommand[$i][2];
                    $this->param['trigger'] = $this->newCommand->isLink;

                    break;
                }
            }
        } else {
            $this->param = $undefinedText;
            $key = 'command';
        }

        if ($key == 'null') {
            $undefinedText = $this->newCommand->undefinedText($this->commandText, 'end', $this->commandTextFull);
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
     * Сохранение команд пользователя
     */
    protected function saveCommand(): void
    {
        /**
         * Яндекс примерно раз в минуту шлет команду пинг на навык,
         * что бы убедиться что он работает. Поэтому не записываем команду пинг
         **/
        if ($this->commandText != 'ping' && $this->commandText != 'пинг') {
            if (!is_dir('session')) {
                mkdir('session');
            }
            $file = fopen('session/' . $this->clientKey . '.json', 'w');
            fwrite($file, json_encode($this->botParamsJson, JSON_UNESCAPED_UNICODE));
            fclose($file);
        }
    }

    /**
     * Обработка команд
     *
     * @param $key
     *
     * @return bool
     */
    public function command($key): bool
    {
        $trigger = false;
        switch ($key) {
            case 'command':
                $this->textMessage = $this->param[0];
                $this->buttonMessage = $this->param[1] ?? '';
                $this->urlMessage = $this->param[2] ?? '';
                $trigger = $this->param['trigger'] ?? false;
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
                $mat = [
                    'А вот не стоит обзываться!\nЭто крайне не культурно и не прилично',
                    'Эх\nНе хорошо говорить подобные слова\nКак вам не стыдно поступать подобным образом',
                    'А слабо сказать тоже самое только без нецензурных слов😉',
                    'Мат?!?😳\n Ой все!!!\nНе дружу я с тобой!',
                ];
                $this->textMessage = $mat[rand(0, 3)];
                $this->buttonMessage = '';
                $this->urlMessage = '';
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
                if (!$this->isVk) {
                    $this->textMessage .= '\nДля того чтобы выйти из навыка, просто скажите "Алиса хватит" или попращайтесь со мной.';
                }
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case 'goodName':
                $this->textMessage = $this->getGoodName();
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            case -1:
                $this->textMessage = ' ';
                $this->buttonMessage = '';
                $this->urlMessage = '';
                break;

            default:
                $this->getRandomText();
                $this->textMessage = $this->param[0];
                if ($this->param[1] && $this->param[2]) {
                    $this->buttonMessage = ((!$this->isVk) ? $this->param[1] : 'Наш сайт');
                    $this->urlMessage = $this->url . '/' . $this->param[2];
                } else {
                    $this->buttonMessage = '';
                    $this->urlMessage = '';
                }
                break;
        }
        return $trigger;
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