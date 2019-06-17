<?php
/**
 * Created by PhpStorm.
 * User: max18
 * Date: 14.05.2019
 * Time: 8:44
 */

namespace alisa\components;

use alisa\block\AlisaImageCard;

class Navigation
{
    const NEXT = 1;
    const BACKWARD = 2;
    const MAX_ELEMENT = 5;

    /**
     * Отображение списка.
     * Рекомендация!
     * Лучше заполнять свойства image->title и image->footer вручную.
     *
     * @param int $page
     * @param array $data [
     *  'image' => ..., Путь до картинки
     *  'title' => ..., Заголовок для элемента
     *  'desc' => ...,  Описание элемента
     *  'button' => ... Кнопка элемента(если есть)
     * ]
     * @param AlisaImageCard $image
     * @param array $buttons
     * @param array $param [
     *  'title' => ...,        Заголовок карточки
     *  'footerText' => ...,   Текст в футерере
     *  'footerButton' => ..., Кнопка в футере (если есть)
     * ]
     *
     * @return string|null
     */
    public static function showList($page, $data, AlisaImageCard &$image, &$buttons, $param = [])
    {
        $content = null;
        if ($data) {
            if (isset($config['title'])) {
                $content = $param['title'];
            }
            $page = self::getPage($page, $data);
            $image->isItemsList = true;
            $image->title = $content;
            for ($i = $page['start']; $i < $page['count']; $i++) {
                $content .= '- ' . $data[$i]['title'];
                $image->addImages($data[$i]['image'] ?? '', $data[$i]['title'] ?? ' ', $data[$i]['desc'] ?? ' ', $data[$i]['button'] ?? null);
            }

            if (isset($param['footerText'])) {
                $image->footerText = $param['footerText'];
            }
            if (isset($param['footerButton'])) {
                $image->footerButton = $param['footerButton'];
            }

            if (is_array($buttons)) {
                $buttons = array_merge($page['button'], $buttons);
            } else {
                $buttons = $page['button'];
            }
        }
        return $content;
    }

    /**
     * Навигация. Пролистывания вперед или назад в зависимости от параметра type.
     *
     * @param int $type - Тип навигации (вперед или назад)
     * @param array $param - Данные пользователя
     * @param array $data - Массив с данными, которые в дальнейшем необходимо отобразить
     */
    public static function navigate($type, &$param, $data): void
    {
        if (!isset($param['page'])) {
            $param['page'] = 0;
        }
        switch ($type) {
            case self::NEXT:
                $param['page']++;
                $count = count($data);
                $page = (int)($count / self::MAX_ELEMENT);
                if ($count % self::MAX_ELEMENT) {
                    $page++;
                }
                if ($param['page'] >= $page) {
                    $param['page'] = ($page - 1);
                }
                break;
            case self::BACKWARD:
                $param['page']--;
                if ($param['page'] < 0) {
                    $param['page'] = 0;
                }
                break;
        }
    }

    /**
     * Обработка для отображения контента
     * Вернет стартовую и конечную позицию, а также кнопки навигации
     *
     * @param int $page - Текущая страница
     * @param array $data - Массив с данными, которые в дальнейшем необходимо отобразить
     *
     * @return array ['start' => int, 'count' => int, 'button' => array]
     */
    public static function getPage($page, $data = null): array
    {
        $count = self::MAX_ELEMENT;
        $start = $page * $count;
        if (!isset($data[$start])) {
            $start = 0;
        }
        $buttons = [];
        if ($start) {
            $buttons[] = '👈 Назад';
        }
        if (isset($data[$start + $count])) {
            $buttons[] = 'Дальше 👉';
        }
        $count += $start;
        if ($count > count($data)) {
            $count = count($data);
        }
        return ['start' => $start, 'count' => $count, 'button' => $buttons];
    }

    /**
     * Отобразит, на какой странице находится пользователь
     *
     * @param $page
     * @param $data
     *
     * @return string
     */
    public static function getPageInfo($page, $data): string
    {
        if (!isset($data[$page * 5])) {
            $page = 0;
        }
        $pageInfo = ($page + 1) . ' страница из ';

        $count = count($data);
        $maxPage = (int)($count / 5);
        if ($count % 5) {
            $maxPage++;
        }
        $pageInfo .= $maxPage;
        if ($maxPage == 1) {
            $pageInfo = '';
        } else {
            $pageInfo = '\n' . $pageInfo;
        }
        return $pageInfo;
    }
}