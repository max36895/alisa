<?php
/**
 * User: MaxM18
 */
require_once __DIR__ . '/../../processing/Command.php';
require_once __DIR__ . '/../../components/Text.php';

class exampleCommand extends \bot\processing\Command
{
    public $userId = null;

    /**
     * Массив с вопросами
     * @var mixed
     */
    public $gameTexts;

    /**
     * Массив говорящий текс, если пользователь ответил правильно
     * @var array
     */
    public $correct = [
        '#$win$#Поздравляем! Это правильный ответ.\nИ так Следующий вопрос',
        '#$win$#Совершенно верно\nА следующий вопрос звучит так',
        '#$win$#Мы знали что вы сможете ответить правильно\nНадеюсь что на следующий вопрос вы так же ответите',
        '#$win$#Барабанная дробь. И это правильный ответ\nИ так Следующий вопрос',
        '#$win$#Не зря я в вас верю. Вы совершенно правы\nСледующий вопрос в студию',
        '#$win$#Если бы я мог, то хлопал вам стоя. Вы совершенно правы.\nИ следующий вопрос в студию',
    ];
    /**
     * Массив говорящий текс, если пользователь ответил правильно
     * @var array
     */
    public $notCorrect = [
        '#$fail$#К сожалению вы не правы\nИ так Следующий вопрос',
        '#$fail$#К сожалению все иначе\nа теперь ответьте на этот вопрос',
        '#$fail$#К сожалению это не так\nСледующий вопрос',
        '#$fail$#Эх. К сожелению вы не правы. Может вам стоит перекусить\nПопробуйте ответить на данный вопрос',
        '#$fail$#Вы не правы. Псс тёмный шоколад улучшает память, остроту внимания, скорость реакции и умение решать проблемы, за счёт увеличения притока крови к мозгу. Используйте данную информацию.\nА вот следующий вопрос.'
    ];

    /**
     * Переопределение звукового сопровождения.
     * Актуально если Алиса не верно ставит ударение в каком либо слове.
     * @param string $tts
     * @return string
     */
    public function generateTTS($tts): string
    {
        return parent::generateTTS($tts);
    }

    /**
     * Вставляем свои звуки в навык.
     * П.с. Текст в навыке должен выглядеть примерно так:
     * text $#win#$ text
     * В данном случае воспроизведется звук победы.
     *
     * @param $text
     * @param $isShowSound
     * @param null $customParams
     *
     * @return string
     */
    public function getSound($text, $isShowSound, $customParams = null): string
    {
        $sounds = [
            ['key' => '#$win$#', 'sounds' => ['<speaker audio=\"alice-sounds-game-win-1.opus\">', '<speaker audio=\"alice-sounds-game-win-2.opus\">', '<speaker audio=\"alice-sounds-game-win-3.opus\">',]],
            ['key' => '#$fail$#', 'sounds' => ['<speaker audio=\"alice-sounds-game-loss-1.opus\">', '<speaker audio=\"alice-sounds-game-loss-2.opus\">', '<speaker audio=\"alice-sounds-game-loss-3.opus\">',]],
            ['key' => '#$people$#', 'sounds' => ['<speaker audio=\"alice-sounds-human-cheer-1.opus\">', '<speaker audio=\"alice-sounds-human-cheer-2.opus\">',]],
            ['key' => '#$level$#', 'sounds' => ['<speaker audio=\"alice-sounds-game-powerup-1.opus\">', '<speaker audio=\"alice-sounds-game-powerup-2.opus\">',]],
        ];
        return parent::getSound($text, $isShowSound, $sounds);
    }

    /**
     * Переопределяем ответ.
     * Актуально, если вы захотите использовать какой-то персонализированный ответ
     *
     * @param $key - Ключ команды.
     * @param $text
     * @param $button
     * @param $link
     *
     * @return array
     */
    public function getUpdateLink($key, $text, $button, $link): array
    {
        switch ($key) {
            case 'thank':
            case 'by':
            case 'name':
            case 'help':
            case 'game':
            case 'wearied':
            case 'next':
                $button = 'Оцените нас';
                $link = 'https://dialogs.yandex.ru/store/skills/2215ddd4-pomoshnik-kinoman';
                break;
        }
        return [$text, $button, $link];
    }

    public function __construct()
    {
        $this->gameTexts = include __DIR__ . '/game.php';
        parent::__construct();
        $this->updateLink = true; // Указывает что некоторые обработанные ответы изменятся
    }

    /**
     * Получение предыдущей команды, актуально если вы хотите передать другие стандартные кнопки.
     * @param null $buttons
     * @return bool
     */
    protected function prevCommand($buttons = null): bool
    {
        return parent::prevCommand($buttons);
    }

    /**
     * Получаем пользовательскте данные.
     * @return bool
     */
    public function isParams(): bool
    {
        if (!$this->param) {
            if ($this->prevCommand()) {
                $this->param['example'] = $this->param['example'] ?? ''; // Сам пример
                $this->param['prev'] = $this->param['prev'] ?? '';       // Идентификатор предыдущей команды
            } else {
                $this->param = [];
                $this->param['example'] = '';
                $this->param['prev'] = '';
            }
        }
        return true;
    }

    /**
     * Следующий вопрос
     * @return mixed|string
     */
    protected function next()
    {
        $this->isParams();
        if ($this->param['example']) {
            $this->buttons = ['правда', 'ложь'];
            $result = $this->gameTexts[$this->param['example']];

            if ($result[1]) {
                return "Это правда. Давайте следующий вопрос.";
            } else {
                return 'Данный факт является ложью. А вот и следующий вопрос.';
            }
        }
        return $this->info();
    }

    /**
     * Если утверждение верно
     *
     * @param $res
     * @return string
     */
    protected function isResult($res)
    {
        $this->isParams();
        if ($this->param['example']) {
            $this->buttons = ['правда', 'ложь'];
            if ($this->gameTexts[$this->param['example']][1] == $res) {
                return $this->getRandText($this->correct);
            } else {
                return $this->getRandText($this->notCorrect);
            }
        }
        return $this->help();
    }

    /**
     * Посмотреть инетересный факт
     * @return mixed
     */
    private function info()
    {
        $info = ['1 факт', '2 факт', '3 факт'];
        return $this->getRandText($info);
    }

    /**
     * Вывести помощь по игре
     * @return string
     */
    protected function help()
    {
        return 'Цель игры заключается в ответе является ли данное утверждение правдой или нет\nЧто бы начать игру просто скажите \"Старт\".\nЕсли вы считаете что утверждение верно, то смело говорите \"Правда\"\nЕли вы считаете что утверждение ложно, то так же смело говорите \"Лож\"\nЧто бы выйти из игры просто скажите \"Стоп\"';
    }

    /**
     * Начинаем игру
     * @return mixed
     */
    protected function game()
    {
        $welcome = [
            'Отлично! Тогда приступим.',
            'Очень хорошо. Чтож давайте играть.',
            'Отлично! И вот вопрос.',
            'Очень хорошо. И вот мое утверждение.',
            'Прекрасно! Я всегда верил в вас. И так вот мой вопрос.',
        ];

        $this->buttons = ['правда', 'ложь'];
        return $welcome[rand(0, count($welcome) - 1)];
    }

    /**
     * пользователь устал
     * @return mixed
     */
    protected function wearied()
    {
        $wearieds = [
            'Чтож самое время отдохнуть. Сходите перекусите или просто полежите',
            'Тогда хватит себя мучить. Настало время отдыха. Отдыхайте.',
            'Отдыхайте. Вы молодец и заслужили отдых',
            'Вы отличный человек, и неприменно заслужили отдых. Полежите и отдохните немножко, а хороший фильм и чашечка любимого напитка вам помогут',
            'Всем нам нужен отдых. Отдохните и вы. Сходите перекусить или просто поваляйтесь в кроватке',
        ];
        return $wearieds[rand(0, count($wearieds) - 1)];
    }

    /**
     * Обработка комманд. В зависимости от $index выполяется та или иная обработка.
     *
     * @param $index - ключ комманды, который был указан в allCommand.php
     *
     * @return array
     */
    public function commands($index)
    {
        switch ($index) {
            case 'true':
                $this->isParams();
                $text = $this->isResult(true);
                $this->param['example'] = rand(0, count($this->gameTexts) - 1);
                $text .= '\n' . $this->gameTexts[$this->param['example']][0];
                break;
            case 'false':
                $this->isParams();
                $text = $this->isResult(false);
                $this->param['example'] = rand(0, count($this->gameTexts) - 1);
                $text .= '\n' . $this->gameTexts[$this->param['example']][0];
                break;
            case 'game':
                $this->isParams();
                $text = $this->game();
                $this->param['example'] = rand(0, count($this->gameTexts) - 1);
                $text .= '\n' . $this->gameTexts[$this->param['example']][0];
                break;
            case 'next':
                $this->isParams();
                $text = $this->next();
                $this->param['example'] = rand(0, count($this->gameTexts) - 1);
                $text .= '\n' . $this->gameTexts[$this->param['example']][0];
                break;
            case 'name':
                $text = 'Я навык разработанный MaxImko. И мое предназначение это играть с вами в игру \"Верю не верю\"';
                break;
            case 'wearied':
                $text = $this->wearied();
                break;
            default:
                $text = $this->help();
                break;
        }
        return [$text];
    }

    /**
     * Данная функция вызывается в начале, а так же вызваться в конце
     *
     * @param $text
     * @param string $type если вызвалось в конце значение будет равно end
     * @param string $fullText
     *
     * @return array|null
     */
    public function undefinedText($text, $type = 'text', $fullText = '')
    {
        if (false) {
            /**
             * Так отправлять Карточку пользователю
             */
            $this->image->isBigImage = true; // Указываем что используем карточку
            $this->image->title = 'Title'; // Заполяем заголовок для карточки
            $this->image->description = 'Description'; // Заполяем описание для карточки
            $this->image->button = ['text' => 'button', 'url' => 'https://www.islandgift.ru', 'payload' => 'payload']; // Указываем кнопку, если необходимо.

            /**
             * Так отправлять Список с картинками пользователю
             */
            $this->image->isItemsList = true; // Указываем что использовать список
            $this->image->title = 'Title'; // Заполняем заголовок для списка
            $button = ['text' => 'button', 'url' => 'https://www.islandgift.ru', 'payload' => 'payload']; // Создаем кнопку
            $this->image->addImages('imgDir', 'Title', 'Description', $button); // Добавляем картинки
            $this->image->addImages('imgDir', 'Title', 'Description', null);    //===================
            $this->image->footerText = 'Footer'; // Заполняем поле footer если необходимо
            $this->image->footerButton = $button; // Указываем кнопку для footer`a

            /**
             * Так отправлять Список без картинок пользователю
             */
            $this->image->isItemsList = true; // Указываем что использовать список
            $this->image->title = 'Title'; // Заполняем заголовок для списка
            $button = ['text' => 'button', 'url' => 'https://www.islandgift.ru', 'payload' => 'payload']; // Создаем кнопку
            $this->image->addImages('', 'Title', 'Description', $button); // Добавляем картинки
            $this->image->addImages('', 'Title', 'Description', null);    //===================
            $this->image->isItemsImage = false; // Указываем, что не нужно отображать картинки
            $this->image->footerText = 'Footer'; // Заполняем поле footer если необходимо
            $this->image->footerButton = $button; // казываем кнопку для footer`a

            // Работаем с именами пользователя.
            /**
             * Записываем имя пользователя
             */
            $name = $this->nlu->getFio(); // Смотрим распозналось имя пользователя или нет.
            if ($name['status']) {
                $name = $name['result']; // В случае успеха записываем его
            } else { // Иначе записываем имя, которое получено из ответа

                /**
                 * Использование подобного варианта негативно сказывается на записи имени.
                 * Так как в кажестве имени будет использован весь пользовательский текст.
                 * Здесь желательно либо хранить свою базу имен, или оставить все как есть.
                 *
                 * Помимо этого, нельзя исключать варианты, что иногда пользователь хочет ввести свой псевдоним (Котик, принцесса и тд.)
                 */
                $name = [
                    0 => [
                        "first_name" => $text,
                        "patronymic_name" => '',
                        "last_name" => ''
                    ]
                ];
            }
            $this->param['name'] = $this->nlu->getUserName($name)['result']; // Преобразовываем имя пользователя и записываем его в параметр

            /**
             * Теперь предположим, что пользователь ввел свое имя. И мы спросили его, согласен ли он записать новое имя или нет.
             *
             * Проверка согласен пользователь или нет.
             */
            if (\bot\components\Text::isSayTrue($text)) {
                return ['Ура! Вы изменили имя'];
            } else {
                return ['Увы имя не изменилось!'];
            }

            // Принцип работы функции и ее предназначение
            $this->isParams(); // Получаем все параметры
            switch ($this->param['prev']) {
                case 'user_name':
                    /**
                     * Если у пердыдущей команды был этот ключ, то узнаем пользовательской имя
                     *
                     * Тут логика получения имени
                     */
                    break;
                case 'is_save_user_name':
                    /**
                     * Тут логика, которая понимает сохранить ли это имя пользователя или нет, основываяся на его ответе (Согласен / не согласен)
                     */
                    break;
            }
        }
        return null;
    }
}