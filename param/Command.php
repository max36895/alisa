<?php
/**
 * Created by PhpStorm.
 * User: max18
 * Date: 24.10.2018
 * Time: 9:42
 * Update: 11.12.2018
 */

namespace alisa\param;

use alisa\api\Alisa;
use alisa\api\AlisaImageCard;
use alisa\api\AlisaNlu;

require_once __DIR__ . '/../api/Alisa.php';

/**
 * Class Command
 * @property bool $isLink
 * @property AlisaImageCard $image
 * @property string $userId = null
 * @property array $buttons = null
 * @property array|string $param
 * @property bool $updateLink
 * @property string $botName
 * @property AlisaNlu $nlu
 * @property string|array $payload
 **/
abstract class Command extends Alisa
{
    public $isLink;
    public $payload;

    public $userId = null;
    public $buttons = null;
    public $param;
    public $updateLink; // Указывает что навык запрашивает переопределение ответов

    public $botName;
    public $alisaCommand;

    public function __construct($imageToken = '')
    {
        parent::__construct($imageToken);
        $this->isLink = false;
        $this->param = 'Нет параметров';
        $this->updateLink = false;
        $this->botName = '';
        $this->payload = null;
    }

    public final function setImageToken($token)
    {
        $this->image->setImageToken($token);
    }

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
     * @param $text
     * @param $type
     * @param $fullText
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
     * @return array
     */
    public function getUpdateLink($key, $text, $button, $link): array
    {
        return [$text, $button, $link];
    }

    /**
     * Получает url сайта
     * Актуально для загрузки картинок
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
     * @param $index
     * @return array
     */
    public abstract function commands($index);
}