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
     * Обработка всех символов, из-за которых регулярка может дать сбой
     *
     * @param $pattern
     *
     * @return mixed
     */
    public static function refactPatternText($pattern)
    {
        return str_replace(
            ["\\", '/', '[', ']', '.', '(', ')', '*', '|', '?'],
            ["\\\\", '\/', '\[', '\]', '\.', '\(', '\)', '\*', '\|', '\?'], $pattern);
    }

    /**
     * Добавляет нужное окончание в зависимости от числа
     *
     * @param $num - само число
     * @param $titles - массив из возможных вариантов. массив должен быть типа ['1 значение','2 значение','3 значение']
     * Где:
     * 1 значение - это окончание, которое получится если последняя цифра числа 1
     * 2 значение - это окончание, которое получится если последняя цифра числа от 2 до 4
     * 3 значение - это окончание, если последняя цифра числа от 5 до 9 включая 0
     * Пример:
     * ['Яблоко','Яблока','Яблок']
     * Результат:
     * 1 Яблоко, 21 Яблоко, 3 Яблока, 9 Яблок
     *
     * @param null $index свое значение из массива. Если элемента в массиве с данным индексом нет, тогда параметр опускается.
     *
     * @return mixed
     */
    public static function getEnding($num, $titles, $index = null): string
    {
        if ($index) {
            if (isset($titles[$index])) {
                return $titles[$index];
            }
        }
        if ($num < 0) {
            $num *= -1;
        }
        $cases = array(2, 0, 1, 1, 1, 2);
        return $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
    }

    /**
     * Проверяет есть ли значения по регулярке или нет
     *
     * @param $pattern
     * @param $text
     *
     * @return bool
     */
    public static function isSayPattern($pattern, $text)
    {
        preg_match_all('/' . $pattern . '/umi', $text, $data);
        return (($data[0][0] ?? null) ? true : false);
    }

    /**
     * Поиск определенного слова в тексте
     * Поиск осуществляется как полного текста, так и не полного
     *
     * @param $find - Слово для поиска можно передать массив из слов
     * @param $text - Текст в котором осуществляется поиск
     * @param bool $isAll - Искать любое вхождение слова
     *
     * @return bool
     */
    public static function isSayText($find, $text, $isAll = false)
    {
        $pattern = '';
        if (is_array($find)) {
            foreach ($find as $value) {
                $value = self::refactPatternText($value);
                if ($pattern) {
                    $pattern .= '|';
                }
                $pattern .= '(\b' . $value . '[^\s]+\b)|(\b' . $value . '\b)';
                if ($isAll) {
                    $pattern .= '|(' . $value . ')';
                }
            }
        } else {
            $find = self::refactPatternText($find);
            $pattern = '(\b' . $find . '\b)|(\b' . $find . '[^\s]+\b)';
            if ($isAll) {
                $pattern .= '|(' . $find . ')';
            }
        }
        preg_match_all('/' . $pattern . '/umi', $text, $data);
        return (($data[0][0] ?? null) ? true : false);
    }

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
     * Возвращает определенный символ или несколько символов в тексте
     * Актуально когда нужно получить какой либо символ unicode строки
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