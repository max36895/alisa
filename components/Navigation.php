<?php
/**
 * Created by PhpStorm.
 * User: max18
 * Date: 14.05.2019
 * Time: 8:44
 */

namespace bot\components;

use bot\alisa\AlisaImageCard;

class Navigation
{
    const NEXT = 1;
    const BACKWARD = 2;
    const MAX_ELEMENT = 5;

    /**
     * Отображение списка.
     * Рекомендация!
     * Лучше заполнять свойства image->title и image->footer отдельно.
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
            if (isset($param['title'])) {
                $image->title = $param['title'];
            }
            if ($image->title) {
                $image->title .= self::getPageInfo($page, $data);
            }
            $page = self::getPage($page, $data);
            for ($i = $page['start']; $i < $page['count']; $i++) {
                if (isset($data[$i]['name'])) {
                    if (!isset($data[$i]['title'])) {
                        $data[$i]['title'] = $data[$i]['name'];
                    }
                }
                if (mb_strlen($data[$i]['title'] . ' ' . $data[$i]['description']) < 30) {
                    $content .= '- ' . $data[$i]['title'] . ' ' . $data[$i]['description'];
                } else {
                    $content .= '- ' . $data[$i]['title'];
                }
                $image->addImages($data[$i]['image'] ?? '', $data[$i]['title'] ?? ' ', $data[$i]['description'] ?? ' ', $data[$i]['button'] ?? null);
            }
            if (isset($param['footerText'])) {
                $image->footerText = $param['footerText'];
                if (isset($param['footerButton'])) {
                    $image->footerButton = $param['footerButton'];
                }
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
     * Навигация.
     * Пролистывания вперед или назад в зависимости от параметра type.
     *
     * @param int $type - Тип навигации (вперед или назад)
     * @param array $param - Данные пользователя
     * @param array $data - Массив с данными, которые в дальнейшем необходимо отобразить, необходим для того, чтобы получить количество элементов
     */
    public static function navigate($type, &$param, $data)
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
     * Обработка для отображения нужного контента
     * Вернет стартовую и конечную позицию в списке, а также кнопки навигации
     *
     * @param int $page - Текущая страница
     * @param array $data - Массив с данными, которые в дальнейшем необходимо отобразить
     *
     * @return array ['start' => int, 'count' => int, 'button' => array]
     */
    public static function getPage($page, $data = null)
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
     * @param $page - Текущая страница
     * @param $data - Массив с данными
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