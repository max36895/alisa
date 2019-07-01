<?php
/**
 * Created by PhpStorm.
 * User: max18
 * Date: 14.05.2019
 * Time: 9:13
 */

namespace bot\components;


class Text
{
    /**
     * Обработка всех символов, из-за которых регулярка может дать сбой
     *
     * @param string $pattern - Пользовательский текст, который попадает в регулярное выражение
     *
     * @return string
     */
    public static function refactPatternText($pattern): string
    {
        return str_replace(
            ["\\", '/', '[', ']', '.', '(', ')', '*', '|', '?'],
            ["\\\\", '\/', '\[', '\]', '\.', '\(', '\)', '\*', '\|', '\?'],
            $pattern);
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
     * Проверяет есть ли значения при поиске по регулярке или нет.
     *
     * @param string $pattern - Произвольное регулярное выражение
     * @param string $text - Текст, который нужно обработать
     *
     * @return bool
     */
    public static function isSayPattern($pattern, $text): bool
    {
        preg_match_all('/' . $pattern . '/umi', $text, $data);
        return (($data[0][0] ?? null) ? true : false);
    }

    /**
     * Поиск определенного слова или выражения в тексте
     * Поиск осуществляется как полного текста, так и не полного
     * Т.е. если есть text = "приветик", а мы ищем find="Привет", тогда вернется true
     *
     * @param $find - Слово для поиска можно передать массив из слов
     * @param $text - Текст в котором осуществляется поиск
     * @param bool $isAll - Искать любое вхождение слова
     *
     * @return bool
     */
    public static function isSayText($find, $text, $isAll = false): bool
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
     * Проверяет согласен ли пользователь
     *
     * @param string $text
     *
     * @return bool
     */
    public static function isSayTrue($text): bool
    {
        $pattern = '/(\bда\b)|(\bконечно\b)|(\bсогласен\b)|(подтвер)/umi';
        preg_match_all($pattern, $text, $data);
        return (($data[0][0] ?? null) ? true : false);
    }

    /**
     * Проверяет не соглаен ли пользователь
     *
     * @param string $text
     *
     * @return bool
     */
    public static function isSayFalse($text): bool
    {
        $pattern = '/(\bнет\b)|(\bнеа\b)|(\bне\b)/umi';
        preg_match_all($pattern, $text, $data);
        return (($data[0][0] ?? null) ? true : false);
    }

    /**
     * Проверяет хочет пользователь отменить действие или нет
     *
     * @param string $text
     *
     * @return bool
     */
    public static function isSayCancel($text): bool
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
    public static function resize($text, $size = 950): string
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
     * @param string $text - текс
     * @param int $index - Порядковый номер символа
     * @param int $count - Количество символов для поиска
     *
     * @return string
     */
    public static function getCharUtf($text, $index, $count = 1): string
    {
        return mb_substr($text, $index, $count);
    }

    /**
     * Проверка текста на сходство.
     * В результате вернет статус схожести, а также текст и ключ в массиве
     *
     * Если текста схожи, тогда status = true, и заполняются поля:
     * index - Если был передан массив, тогда вернется его индекс.
     * text - Текст, который оказался максимально схожим.
     *
     * @param string $origText - оригинальный текст. С данным текстом будет производиться сравнение
     * @param string|array $text - Текст для сравнени. можно передать массив из текстов для поиска.
     * @param int $percent - при какой процентной схожести считать что текста одинаковые
     *
     * @return array ['status' => bool, 'index' => int|string, 'text' => string]
     */
    public static function textSimilarity($origText, $text, $percent = 80): array
    {
        $data = [
            'percent' => 0,
            'index' => null
        ];
        if (!is_array($text)) {
            $text = [$text];
        }
        $origText = mb_strtolower($origText);
        foreach ($text as $index => $res) {
            $res = mb_strtolower($res);
            if ($res == $origText) {
                return ['status' => true, 'index' => $index, 'text' => $res];
            }
            $per = 0;
            similar_text($origText, $res, $per);
            if ($data['percent'] < $per) {
                $data = [
                    'percent' => $per,
                    'index' => $index
                ];
            }
        }
        if ($data['percent'] >= $percent) {
            return ['status' => true, 'index' => $data['index'], 'text' => $text[$data['index']]];
        }
        return ['status' => false, 'index' => null, 'text' => null];
    }

    /**
     * Получить рандомное значение из массива
     * В случае если передается строка, тогда возвращается строка.
     * Если массив имеет произвольный ключ. Тоесть, массив имеет вид ['text1'=>'...','text2'=>'...'] вместо ['...','...'],
     * тогда будет использован 1 элемент массива.
     *
     * @param string|array $text
     *
     * @return string
     */
    public static function getRandText($text): string
    {
        if (is_array($text)) {
            $text = ($text[rand(0, count($text) - 1)] ?? current($text));
        }
        return $text;
    }
}