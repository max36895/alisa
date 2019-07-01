<?php
/**
 * User: MaxM18
 */

namespace bot\alisa;

/**
 * Class AlisaButtons
 * @property array $payload      - Произвольный json
 * @property string|array $title - Текст для кнопки
 * @property string|array $url   - Адресс сайта, на который необходимо перейти при клике
 * @property bool $hide          - Отображать как кнопку(true) или ссылку(false)
 * @property array $buttons      - Массив кнопок
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
     * Удалить все кнопки
     */
    public function clearButtons(): void
    {
        $this->buttons = [];
    }

    private function setButton($title, $url, $payload, $hides)
    {
        if (is_array($title)) {
            $index = 0;
            foreach ($title as $tResult) {
                if (!is_array($url)) {
                    $url = [$url];
                }
                if (is_array($hides)) {
                    $hide = $hides[$index] ?? self::B_LINK;
                } else {
                    $hide = $hides;
                }
                $this->addButton($tResult, $url[$index] ?? '', $payload, $hide);
                $index++;
            }
        } else {
            if (is_array($url)) {
                $url = current($url);
            }
            if (is_array($hides)) {
                $hides = current($hides);
            }
            $this->addButton($title, $url, $payload, $hides);
        }
    }

    /**
     * Получить все кнопки, которые необходимо отобразить.
     *
     * @return array|null
     */
    public function getButtons()
    {
        if ($this->title) {
            $this->setButton($this->title, $this->url, $this->payload, $this->hide);
        }
        if (count($this->buttons)) {
            return $this->buttons;
        }
        return null;
    }

    /**
     * Добавить кнопку в виде ссылки
     *
     * @param $title
     * @param string $url
     * @param null $payload
     *
     * @return array
     */
    public function setLink($title, $url = '', $payload = null): array
    {

        $this->setButton($title, $url, $payload, self::B_LINK);

        return $this->buttons;
    }

    /**
     * Добавить кнопку в виде кнопки
     *
     * @param $title
     * @param string $url
     * @param null $payload
     *
     * @return array
     */
    public function setBtn($title, $url = '', $payload = null): array
    {
        $this->setButton($title, $url, $payload, self::B_BTN);

        return $this->buttons;
    }

    /**
     * Возвращает массив для отображения кнопок с ссылками
     *
     * @param $title - текс для кнопки
     * @param $url - адрес сайта, если есть
     * @param null $payload - произвольный json.
     * @param bool $hide - Отображать как кнопку или ссылку
     *
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
     * Добавить кнопку
     *
     * @param $title
     * @param $url
     * @param null $payload
     * @param bool $hide
     */
    public function addButton($title, $url, $payload = null, $hide = self::B_LINK): void
    {
        if (is_array($title)) {
            $title = current($title);
        }
        if (is_array($url)) {
            $url = current($url);
        }
        if (is_array($hide)) {
            $hide = current($hide);
        }

        $btn = $this->getButtonData($title, $url, $payload, $hide);
        if ($btn) {
            $this->buttons[] = $btn;
        }
    }
}