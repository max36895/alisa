<?php
/**
 * Created by PhpStorm.
 * User: max18
 * Date: 18.06.2019
 * Time: 8:47
 */

namespace bot\components;


class Dop
{
    const Y_MONEY_LINK = 'https://yasobe.ru/na/maximco';

    /**
     * Функция, отвечающая за добавления данных.
     * Проще говоря заполняются button и link
     *
     * @param $button - Ссылка на кнопку
     * @param $link - Ссылка на url
     * @param $bText - Текст для кнопки
     * @param $lText - Содержимое кнопки (В основном это url)
     */
    private static function add(&$button, &$link, $bText, $lText)
    {
        if (is_array($button)) {
            if (!is_array($link)) {
                $link = [$link];
            }
            $button = array_merge($button, [$bText]);
            $link = array_merge($link, [$lText]);
        } else {
            if ($button) {
                $button = [$button, $bText];
                $link = [$link, $lText];
            } else {
                $button = $bText;
                $link = $lText;
            }
        }
    }

    /**
     * Функция, которая просит пользователя поблагодарить разработчика.
     * @param $button - Ссылка на кнопку
     * @param $link - Ссылка на url
     */
    public static function addThanks(&$button, &$link)
    {
        $thanks = [
            'Поблагодарить',
            'Отблагодарить',
            'Пожертвовать',
            'Поблагодарить разработчика',
            'Дать денежку',
        ];
        if (rand(0, 5) == 3) {
            $site = 'https://money.yandex.ru/to/410014603054118';
        } else {
            $site = self::Y_MONEY_LINK;
        }
        self::add($button, $link, $thanks[rand(0, 4)], $site);
    }

    /**
     * Функция, которая заполнит текст контентом, необходимом в том случе, когда вы просите пользователя оставить отзыв на навык.
     *
     * @param $button - Ссылка на кнопку.
     * @param $link - Ссылка на url
     * @param $site - Сайт, на который будет произведен переход
     * @param bool $isBy - Проверяет прощается пользователь или нет. От параметра зависит текст для проедложения оставить отзыв
     * @param bool $isYour - Проверка на то, как обращаться к пользователю на Вы(true) или на Ты(false)
     */
    public static function addReview(&$button, &$link, $site, $isBy = false, $isYour = true)
    {
        if ($isBy) {
            $review = [
                'оставить отзыв',
                'оценить навык',
                'написать отзыв',
            ];
            self::add($button, $link, ('Не ' . (($isYour) ? 'хотите' : 'хочешь') . ' ' . $review[rand(0, 2)] . '?'), $site);
        } else {
            $review = [
                'Оставить отзыв',
                'Оценить навык',
                'Написать отзыв',
            ];
            self::add($button, $link, $review[rand(0, 2)], $site);
        }
    }

}