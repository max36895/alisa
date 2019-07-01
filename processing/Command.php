<?php
/**
 * User: MaxM18
 */

namespace bot\processing;

use bot\alisa\Alisa;
use bot\components\Text;

/**
 * Class Command
 * @property string $origText - Оригинальный текст пользователя
 *
 * @property string $userId = null - Идентификатор пользователя
 * @property array $meta - Мета информация (часовой пояс и другие показатели)
 * @property array $buttons = null - Кнопки в виде кнопок для отображения
 * @property array|string $param - Пользовательские данные
 * @property bool $updateLink - Переопределять стандартные команды или нет
 * @property string $botName - Имя ассиатента
 *
 * @property string|array $payload - Произвольный json передается, если пользователь нажал на кнопку, в которой находился payload
 * @property string $sessionDir - Путь для сохранения пользовательских данных. Не рекомендуется изменять в интерфейсе этого класса.
 *                                Для изменения деректории стоит вызвать метод setSessionDir класса Bot
 **/
abstract class Command extends Alisa
{
    public $origText;
    public $isLink;
    public $payload;
    public $meta;

    public $userId = null;
    public $buttons = null;
    public $param;
    public $updateLink; // Указывает что навык запрашивает переопределение ответов

    public $botName;

    public $sessionDir = '';

    public function __construct($imageToken = '')
    {
        parent::__construct($imageToken);
        $this->param = null;
        $this->updateLink = false;
        $this->botName = '';
        $this->payload = null;
        $this->sessionDir = '';
    }

    /**
     * Установить токен для картинки
     *
     * @param $token
     */
    public final function setImageToken($token)
    {
        $this->image->setImageToken($token);
    }

    /**
     * Получить рандомное значение из массива
     *
     * @param $text
     *
     * @return string
     */
    public final function getRandText($text)
    {
        return Text::getRandText($text);
    }

    /**
     * Получение последней команды пользователя
     *
     * @param null $buttons
     *
     * @return bool
     */
    protected function prevCommand($buttons = null): bool
    {
        if ($this->param) {
            return true;
        }
        if ($this->userId !== null) {
            if (!is_dir($this->sessionDir . 'session')) {
                mkdir($this->sessionDir . 'session');
            }
            $fileName = ($this->sessionDir . 'session/' . $this->userId . '.json');
            if (is_file($fileName)) {
                $file = fopen($fileName, 'r');
                $this->param = json_decode(fread($file, filesize($fileName)), true);
                fclose($file);
                return true;
            }
        }

        if ($buttons) {
            $this->buttons = $buttons;
        }

        return false;
    }

    /**
     * Обработка непонятного текста
     * В некоторых навыках необходим
     *
     * @param $text - Текст пользователя
     * @param $type - Функция вызывается 2 раза в начале, и в самом конце, если не удалось найти команду, которая обработает запрос пользователя. В последнем вызове равен 'end'
     * @param $fullText - Полный текст пользователя. Может быть пустым.
     *
     * @return null|array
     */
    public function undefinedText($text, $type = 'text', $fullText = '')
    {
        return null;
    }

    /**
     * Обработка и преобразование tss
     *
     * @param string $tts
     * @return string
     */
    public function generateTTS($tts): string
    {
        return $tts;
    }

    /**
     * Получаем параметры, если вдруг они не проинициализированы
     */
    public final function getParams(): void
    {
        if (!$this->param) {
            $this->isParams();
        }
    }

    /**
     * Инициализация команд пользователя.
     * Проще говоря здесь происходит инициализация $param
     *
     * Рекомендуемое содержимое.
     *
     * if (!$this->param) { // Проверяем не инициализирован ли $param
     * if ($this->prevCommand()) { // получаем пользовательские данные
     * $this->param['prev'] = $this->param['prev'] ?? ''; // Заполняем данные, в случае если блок не найден, то он создается со стандартными значениями
     * } else {
     * $this->param = []; // инициализируем $param
     * $this->param['prev'] = '';
     * }
     * }
     * return true;
     *
     * @return bool
     */
    public abstract function isParams();

    /**
     * Обновление или замена изначальных значений
     *
     * @param $key - ключ
     * @param $text - выводимый текст
     * @param $button - текст на кнопке
     * @param $link - ссылка на сайт
     *
     * @return array
     */
    public function getUpdateLink($key, $text, $button, $link): array
    {
        return [$text, $button, $link];
    }

    /**
     * Получает url сайта
     * Актуально для загрузки картинок.
     * Данный метод желательно переинициализировать по необходимости.
     *
     * Например вы загружаете картинки с ресурса https://example.com, тогда вы просто напросто возвращаете = https://example.com/
     * И отправлять картинки в виде:
     * $this->image->imgDir = 'img.png';
     * Вместо:
     * $this->image->imgDir = 'https://example.com/img.png';
     *
     * @return string
     */
    public function getHost(): string
    {
        return 'https://www.islandgift.ru/';
    }

    /**
     * Класс вспомогательных команд. Возвращает массив из 3 переменных
     * 1 - Тело ответа
     * 2 - Текст для кнопки
     * 3 - Ссылка на ресурс или сайт
     *
     * П.с Если нет ссылок, то можно просто вернуть массив вида
     * ['текст']
     *
     * @param $index
     *
     * @return array
     */
    public abstract function commands($index);
}