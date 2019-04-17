<?php
/**
 * User: MaxM18
 */
require_once __DIR__ . '/../../param/Command.php';

class newCommand extends \alisa\param\Command
{
    public $alisaCommand;
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
            case 'hello':
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
        $this->alisaCommand = new AlisaCommand();
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
     * Следующий вопрос
     * @return mixed|string
     */
    protected function next()
    {
        if ($this->prevCommand()) {
            $this->buttons = ['правда', 'ложь'];
            $result = json_decode($this->alisaCommand->param, true);

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
     * @return mixed|string
     */
    protected function isTrue()
    {
        if ($this->prevCommand()) {
            $this->buttons = ['правда', 'ложь'];
            $result = json_decode($this->alisaCommand->param, true);
            if ($result[1]) {
                return $this->correct[rand(0, count($this->correct) - 1)];
            } else {
                return $this->notCorrect[rand(0, count($this->notCorrect) - 1)];
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
        $info = include __DIR__ . '/../../param/information.php';
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
     * Если утверждение ложное
     * @return mixed|string
     */
    protected function isFalse()
    {
        if ($this->prevCommand()) {
            $this->buttons = ['правда', 'ложь'];
            $result = json_decode($this->alisaCommand->param, true);

            if ($result[1] == false) {
                return $this->correct[rand(0, count($this->correct) - 1)];
            } else {
                return $this->notCorrect[rand(0, count($this->notCorrect) - 1)];
            }
        }
        return $this->help();
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
                $text = $this->isTrue();
                $this->param = $this->gameTexts[rand(0, count($this->gameTexts) - 1)];
                $text .= '\n' . $this->param[0];
                break;
            case 'false':
                $text = $this->isFalse();
                $this->param = $this->gameTexts[rand(0, count($this->gameTexts) - 1)];
                $text .= '\n' . $this->param[0];
                break;
            case 'game':
                $text = $this->game();
                $this->param = $this->gameTexts[rand(0, count($this->gameTexts) - 1)];
                $text .= '\n' . $this->param[0];
                break;
            case 'next':
                $text = $this->next();
                $this->param = $this->gameTexts[rand(0, count($this->gameTexts) - 1)];
                $text .= '\n' . $this->param[0];
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
        return [$text, '', ''];
    }

    /**
     * Данная функция вызывается в начале, а так же вызваться в конце
     *
     * @param $text
     * @param string $type если вызвалось в конце значение будет равно end
     * @param string $fullText
     *
     * @return array|null|void
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
            $this->image->footerButton = $button; // казываем кнопку для footera

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
        }
    }
}