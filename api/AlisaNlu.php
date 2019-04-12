<?php
/**
 * Created by PhpStorm.
 * User: max18
 * Date: 21.01.2019
 * Time: 15:23
 */

namespace alisa\api;

/**
 * Class AlisaNlu
 * @property array $nlu
 */
class AlisaNlu
{
    public $nlu;
    const T_FIO = 'YANDEX.FIO';
    const T_GEO = 'YANDEX.GEO';
    const T_DATETIME = 'YANDEX.DATETIME';
    const T_NUMBER = 'YANDEX.NUMBER';

    /**
     * Получение данных по выбранному типу
     *
     * @param $nluType
     * @return array|null
     */
    private function getData($nluType)
    {
        $result = null;
        for ($i = 0; $i < count($this->nlu['entities']); $i++) {
            $data = $this->nlu['entities'][$i] ?? null;
            if (($data['type'] ?? null) && $data['type'] == $nluType) {
                if ($result === null) {
                    $result = [];
                }
                $result[] = $data['value'];
            }
        }
        return $result;
    }

    /**
     * Переводит текст в нормальный вид. 1 символ с заглавной буквы.
     * @param $text
     * @return string
     */
    protected function getCapital($text): string
    {
        return (mb_strtoupper(mb_substr($text, 0, 1))) . (mb_substr($text, 1));

    }

    /**
     * Вернет имя
     *
     * @param $fio
     * @return array
     */
    public function getUserName($fio): array
    {
        if ($fio) {
            $firstName = $fio[0]['first_name'] ?? '';
            $patronymicName = $fio[0]['patronymic_name'] ?? '';
            $lastName = $fio[0]['last_name'] ?? '';
            $result = $this->getCapital($lastName) . ' ' . $this->getCapital($firstName) . ' ' . $this->getCapital($patronymicName);
            return ['status' => true, 'result' => trim($result)];
        }
        return ['status' => false, 'result' => ''];
    }

    /**
     * Получение ФИО, которые сгенерировал Яндекс
     *
     * Возвращается массив типа:
     * ['status'=>bool,'result'=>array]
     *
     * Если 'status' == true, значит значение найдено. Иначе значение найти не удалось.
     * 'result' представляет из себя массив типа
     * [
     *  [
     *      "first_name" => Имя
     *      "patronymic_name" => Отчество
     *      "last_name" => Фамилия
     *  ]
     * ]
     *
     * @return array
     */
    public function getFio(): array
    {
        $status = false;
        $fio = $this->getData(self::T_FIO);
        if ($fio) {
            $status = true;
        }
        return ['status' => $status, 'result' => $fio];
    }

    /**
     * Получение Местоположение, которое сгенерировал Яндекс
     *
     * Возвращается массив типа:
     * ['status'=>bool,'result'=>array]
     *
     * Если 'status' == true, значит значение найдено. Иначе значение найти не удалось.
     * 'result' представляет из себя массив типа
     * [
     *  [
     *      "country" => Страна
     *      "city" => Город
     *      "street" => Улица
     *      "house_number" => Номер дома
     *      "airport" => Название аэропорта
     *  ]
     * ]
     *
     * @return array
     */
    public function getGeo(): array
    {
        $status = false;
        $geo = $this->getData(self::T_GEO);
        if ($geo) {
            $status = true;
        }
        return ['status' => $status, 'result' => $geo];
    }

    /**
     * Получение Даты и времени, которые сгенерировал Яндекс
     *
     * Возвращается массив типа:
     * ['status'=>bool,'result'=>array]
     *
     * Если 'status' == true, значит значение найдено. Иначе значение найти не удалось.
     * 'result' представляет из себя массив типа
     * [
     *  [
     *      "year" => Точный год
     *      "year_is_relative" => Признак того, что в поле year указано относительное количество лет;
     *      "month" => Месяц
     *      "month_is_relative" => Признак того, что в поле month указано относительное количество месяцев
     *      "day" => День
     *      "day_is_relative" => Признак того, что в поле day указано относительное количество дней
     *      "hour" => Час
     *      "hour_is_relative" => Признак того, что в поле hour указано относительное количество часов
     *      "minute" => Минута
     *      "minute_is_relative" => Признак того, что в поле minute указано относительное количество минут.
     *  ]
     * ]
     *
     * @return array
     */
    public function getDateTime(): array
    {
        $status = false;
        $dataTime = $this->getData(self::T_DATETIME);
        if ($dataTime) {
            $status = true;
        }
        return ['status' => $status, 'result' => $dataTime];
    }

    /**
     * Получение Числа, которое сгенерировал Яндекс
     *
     * Возвращается массив типа:
     * ['status'=>bool,'result'=>array]
     *
     * Если 'status' == true, значит значение найдено. Иначе значение найти не удалось.
     * 'result' представляет из себя массив типа
     * [
     *  [
     *      "integer" => Целое число
     *      "float" => Десятичная дробь
     *  ]
     * ]
     *
     * @return array
     */
    public function getNumber(): array
    {
        $status = false;
        $number = $this->getData(self::T_NUMBER);
        if ($number) {
            $status = true;
        }
        return ['status' => $status, 'result' => $number];
    }
}