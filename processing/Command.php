<?php
/**
 * User: MaxM18
 */

namespace alisa\processing;

use alisa\block\Alisa;
use alisa\block\AlisaImageCard;
use alisa\block\AlisaNlu;

require_once __DIR__ . '/../block/Alisa.php';

/**
 * Class Command
 * @property string $origText - Оригинальный текст пользователя
 * @property bool $isLink
 * @property AlisaImageCard $image
 * @property string $userId = null
 * @property array $meta
 * @property array $buttons = null
 * @property array|string $param
 * @property bool $updateLink
 * @property string $botName
 * @property AlisaNlu $nlu
 * @property string|array $payload
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

    public function __construct($imageToken = '')
    {
        parent::__construct($imageToken);
        $this->isLink = false;
        $this->param = 'Нет параметров';
        $this->updateLink = false;
        $this->botName = '';
        $this->payload = null;
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
        if (is_array($text)) {
            $text = $text[rand(0, count($text) - 1)];
        }
        return $text;
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
            if (!is_dir('session')) {
                mkdir('session');
            }
            $fileName = 'session/' . $this->userId . '.json';
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
     * @return string
     */
    public function getParam()
    {
        return '';
    }

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
        return '';
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