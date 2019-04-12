<?php
/**
 * Created by PhpStorm.
 * User: max18
 * Date: 06.03.2019
 * Time: 13:37
 */

namespace alisa\api;

require_once __DIR__ . '/AlisaSound.php';
require_once __DIR__ . '/AlisaNlu.php';
require_once __DIR__ . '/AlisaImageCard.php';
require_once __DIR__ . '/AlisaButtons.php';

/**
 * Class Alisa
 * @property AlisaNlu $nlu
 * @property AlisaImageCard $image
 * @property AlisaButtons $button
 */
class Alisa extends AlisaSound
{
    public $nlu;
    public $image;
    public $button;

    public function __construct($imageToken)
    {
        $this->nlu = new AlisaNlu();
        $this->image = new AlisaImageCard($imageToken);
        $this->button = new AlisaButtons();
    }

    /**
     * Проверка что есть картинка или галлерея
     *
     * @return bool
     */
    final public function getIsImage(): bool
    {
        return ($this->image->isBigImage || $this->image->isItemsList);
    }

    /**
     * Получить массив для отображения картинки или галлереи
     *
     * @param $host
     * @return array|null
     */
    final public function getImage($host)
    {
        $sendImage = null;
        if ($this->getIsImage()) {
            if ($this->image->isItemsList) {
                $sendImage = $this->image->sendItemsList($host);
                if ($sendImage) {
                    return $sendImage;
                }
            }
            if ($this->image->isBigImage) {
                $this->image->description = $this->getSound($this->image->description, false);
                $sendImage = $this->image->sendBigImage($host);
            }
        }
        return $sendImage;
    }

    /**
     * Добавить кнопку в виде кнопки
     *
     * @param $title
     * @param null $url
     * @param null $payload
     */
    final function addButtons($title, $url = null, $payload = null): void
    {
        $this->button->getBtn($title, $url, $payload);
    }

    /**
     * Добавить кнопку в виде ссылки
     *
     * @param $title
     * @param null $url
     * @param null $payload
     */
    final function addLinks($title, $url = null, $payload = null): void
    {
        $this->button->getLink($title, $url, $payload);
    }

    /**
     * Инициализация кнопок
     *
     * @param $title
     * @param null $url
     * @param null $payload
     * @param bool $type
     */
    final public function initButtons($title, $url = null, $payload = null, $type = AlisaButtons::B_BTN): void
    {
        $this->button->clearButtons();
        switch ($type) {
            case AlisaButtons::B_BTN:
                $this->button->getBtn($title, $url, $payload);
                break;
            case AlisaButtons::B_LINK:
                $this->button->getLink($title, $url, $payload);
                break;
        }
    }

    /**
     * Получить данные для кнопок
     *
     * @return array|null
     */
    final public function getButtons()
    {
        return $this->button->getButtons();
    }

    /**
     * Обработка звуков
     *
     * @param $text
     * @param $isShowSound
     * @param null $customParams
     * @return mixed|null
     */
    public function getSound($text, $isShowSound, $customParams = null)
    {
        if ($customParams == null && (strpos($text, '#$Sound$#') !== false)) {
            $customParams = [
                [
                    'key' => '#$Sound$#',
                    'sounds' => [
                        '<speaker audio=\"alice-sounds-game-win-1.opus\">',
                        '<speaker audio=\"alice-sounds-game-win-2.opus\">',
                        '<speaker audio=\"alice-sounds-game-win-3.opus\">',
                        '<speaker audio=\"alice-sounds-game-8-bit-coin-1.opus\">',
                        '<speaker audio=\"alice-sounds-game-8-bit-coin-2.opus\">',
                        '<speaker audio=\"alice-sounds-game-8-bit-phone-1.opus\">',
                        '<speaker audio=\"alice-sounds-game-boot-1.opus\">',
                    ]
                ]
            ];
        }

        return parent::getSound($text, $isShowSound, $customParams);
    }

    /**
     * Установить значение nlu
     * @param $nlu
     */
    final public function setNlu($nlu): void
    {
        $this->nlu->nlu = $nlu;
    }

    /**
     * Обработка nlu
     * По умолчанию идет обработка имени
     *
     * @param $nlu
     * @return array
     */
    public function nluGenerate($nlu = null): array
    {
        if ($nlu) {
            $this->setNlu($nlu);
        }
        return $this->nlu->getUserName($this->nlu->getFio()['result']);
    }
}