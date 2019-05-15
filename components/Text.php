<?php
/**
 * Created by PhpStorm.
 * User: max18
 * Date: 14.05.2019
 * Time: 9:13
 */

namespace alisa\components;


class Text
{
    /**
     * Проверяет согласен ли пользователь на что-либо
     *
     * @param $text
     *
     * @return bool
     */
    public static function isSayTrue($text)
    {
        $pattern = '/(\bда\b)|(\bконечно\b)|(\bсогласен\b)|(подтвер)/umi';
        preg_match_all($pattern, $text, $data);
        return (($data[0][0] ?? null) ? true : false);
    }

    /**
     * Проверяет не соглаен ли пользователь на что-либо
     *
     * @param $text
     *
     * @return bool
     */
    public static function isSayFalse($text)
    {
        $pattern = '/(\bнет\b)|(\bнеа\b)|(\bне\b)/umi';
        preg_match_all($pattern, $text, $data);
        return (($data[0][0] ?? null) ? true : false);
    }

    /**
     * Проверяет хочет пользователь отменить действие или нет
     *
     * @param $text
     *
     * @return bool
     */
    public static function isSayCancel($text)
    {
        $pattern = '/(\bотмена\b)|(\bотменить\b)/umi';
        preg_match_all($pattern, $text, $data);
        return (($data[0][0] ?? null) ? true : false);
    }

    /**
     * Обрезание текста до нужной длины,
     * А так же преобразование лишних символов
     *
     * @param string $text
     * @param int $size
     *
     * @return string
     */
    public static function resize($text, $size = 950)
    {
        if (mb_strlen($text, 'utf-8') > $size) {
            $text = (mb_substr($text, 0, $size) . '...');
        }
        return str_replace(['\n', '\"'], ["\n", '"'], $text);
    }

    /**
     * Возвращает определенный символ или несколько символов в текста
     *
     * @param $text - текс
     * @param $index - Порядковый номер символа
     * @param int $count - Количество символов для поиска
     *
     * @return string
     */
    public static function getCharUtf($text, $index, $count = 1)
    {
        return mb_substr($text, $index, $count);
    }
}