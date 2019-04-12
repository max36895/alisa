<?php
/**
 * Created by PhpStorm.
 * User: max18
 * Date: 06.03.2019
 * Time: 11:58
 */

namespace alisa\api;

/**
 * Class AlisaButtons
 * @property array $payload
 * @property string|array $title
 * @property string|array $url
 * @property bool $hide
 */
class AlisaButtons
{
    public const B_LINK = false;
    public const B_BTN = true;
    public $payload;
    public $title;
    public $hide;
    public $url;

    protected $buttons;

    public function __construct()
    {
        $this->payload = null;
        $this->title = null;
        $this->hide = self::B_LINK;
        $this->buttons = [];
    }

    /**
     *
     */
    public function clearButtons(): void
    {
        $this->buttons = [];
    }

    /**
     * @return array|null
     */
    public function getButtons()
    {
        if ($this->title) {
            $this->addButton($this->title, $this->url, $this->payload, $this->hide);
        }
        if (count($this->buttons)) {
            return $this->buttons;
        }
        return null;
    }

    /**
     * @param $title
     * @param string $url
     * @param null $payload
     * @return array
     */
    public function getLink($title, $url = '', $payload = null): array
    {
        if (is_array($title)) {
            $index = 0;
            foreach ($title as $data) {
                if (!is_array($url)) {
                    $url = [$url];
                }
                $this->addButton($data, $url[$index] ?? '', $payload, self::B_LINK);
                $index++;
            }
        } else {
            $this->addButton($title, $url, $payload, self::B_LINK);
        }
        return $this->buttons;
    }

    /**
     * @param $title
     * @param string $url
     * @param null $payload
     * @return array
     */
    public function getBtn($title, $url = '', $payload = null): array
    {
        if (is_array($title)) {
            $index = 0;
            foreach ($title as $data) {
                if (!is_array($url)) {
                    $url = [$url];
                }
                $this->addButton($data, $url[$index] ?? '', $payload, self::B_BTN);
                $index++;
            }
        } else {
            $this->addButton($title, $url, $payload, self::B_BTN);
        }
        return $this->buttons;
    }

    /**
     * Возвращает массив для отображения кнопок с ссылками
     *
     * @param $title - текс для кнопки
     * @param $url - адрес сайта, если есть
     * @param null $payload - произвольный json. Зачем нужен непонятно. По умолчанию равен function(){}
     * @param bool $hide - Отображать как кнопку или ссылку
     * @return null|array
     */
    public function getButtonData($title, $url, $payload = null, $hide = self::B_LINK)
    {
        $title = (string)$title;
        if ($title || $title == '0') {
            $btn = [
                'title' => $title,
                'hide' => $hide
            ];
            if ($payload) {
                $btn['payload'] = $payload;
            }
            if ($url) {
                if (strpos($url, 'utm_source') === false) {
                    if (strpos($url, '?') !== false) {
                        $url .= '&';
                    } else {
                        $url .= '?';
                    }
                    $url .= 'utm_source=Yandex_Alisa&utm_medium=cpc&utm_campaign=phone';
                }
                $btn['url'] = $url;
            }
            return $btn;
        }
        return null;
    }

    /**
     * @param $title
     * @param $url
     * @param null $payload
     * @param bool $hide
     */
    public function addButton($title, $url, $payload = null, $hide = self::B_LINK): void
    {
        $btn = $this->getButtonData($title, $url, $payload, $hide);
        if ($btn) {
            $this->buttons[] = $btn;
        }
    }
}