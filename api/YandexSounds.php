<?php
/**
 * Created by PhpStorm.
 * User: max18
 * Date: 28.06.2019
 * Time: 15:10
 */

namespace bot\api;

use \Exception;

class YandexSounds extends YandexService
{
    /**
     * Для получения токена перейти по ссылке
     * https://tech.yandex.ru/dialogs/alice/doc/resource-upload-docpage/ - Документация
     * https://oauth.yandex.ru/verification_code - Получение токена
     */
    const SOUND_TOKEN = '<Токен, который мы получили при регистрации>';

    /**
     * Идентификатор навыка. Можно получить в запросе навыка(Блок session поле skill_id). Или из url навыка в панели для разработчиков.
     * @var string null
     */
    public $skillsId;

    public final function __construct($oauth = null)
    {
        if (!$oauth) {
            $oauth = self::SOUND_TOKEN;
        }
        $this->skillsId = null;
        parent::__construct($oauth);
    }

    /**
     * Куда отправляем запрос.
     *
     * @return string
     */
    public final function getUrl()
    {
        return 'https://dialogs.yandex.net/api/v1/';
    }

    /**
     * Установить токен.
     *
     * @param $token
     */
    public function setSoundToken($token)
    {
        $this->setOauth($token);
    }

    /**
     * Получить токен.
     *
     * @return string
     */
    public function getSoundToken()
    {
        return $this->getOauth();
    }

    /**
     * Проверить занятое место
     *
     * Вернет массив
     * - total - Все доступное место
     * - used - Занятое место
     *
     * @return null|array['total' => int, 'used' => int]
     */
    public function checkOutPlace()
    {
        try {
            $query = $this->call($this->getUrl() . 'status');
            if (isset($query['sounds']['quota'])) {
                return $query['sounds']['quota'];
            }
            $this->setError($query);
            throw new Exception('YandexSounds::checkOutPlace() Error: Не удалось проверить занятое место');
        } catch (Exception $e) {
            $this->logging($e, true);
            return null;
        }
    }

    /**
     * Загрузка изображения из файла
     *
     * Вернет массив
     * - id - Идентификатор изображения
     * - origUrl - Адрес изображения.
     *
     * @param $sound - Расположение картинки на сервере
     *
     * @return null|array['id' => string, 'origUrl' => string]
     */
    public function downloadSound($sound)
    {
        try {
            if ($this->skillsId) {
                $query = $this->call($this->getUrl() . 'skills/' . $this->skillsId . '/sounds', array('header' => array(self::HEADER_FORM_DATA), 'file' => $sound));
                if (isset($query['sound']['id'])) {
                    return $query['sound'];
                } else {
                    $this->setError($query);
                    throw new Exception('YandexSounds::downloadSound() Error: Не удалось загрузить мелодию: ' . $sound);
                }
            }
            $this->setError('Не выбран навык');
            throw new Exception('YandexSounds::downloadSound() Error: Не выбран навык');
        } catch (Exception $e) {
            $this->logging($e, true);
            return null;
        }
    }

    /**
     * Просмотр всех загруженных изображений
     *
     * Вернет массив из массива изображений
     * - id - Идентификатор изображения
     * - origUrl - Адрес изображения.
     *
     * @return null|array[['id' => string, 'origUrl' => string],...]
     */
    public function getLoadedSounds()
    {
        try {
            if ($this->skillsId) {
                $query = $this->call($this->getUrl() . 'skills/' . $this->skillsId . '/sounds');
                if (isset($query['images'])) {
                    return $query['images'];
                } else {
                    $this->setError($query);
                    throw new Exception('YandexSounds::getLoadedSounds() Error: Не удалось получить список загруженных мелодий');
                }
            }
            $this->setError('Не выбран навык');
            throw new Exception('YandexSounds::getLoadedSounds() Error: Не выбран навык');
        } catch (Exception $e) {
            $this->logging($e, true);
            return null;
        }
    }

    /**
     * Удаление выбранной картинки
     * В случае успеха вернет 'ok'
     *
     * @param $imgId - Идентификатор картинки, которую необходимо удалить.
     *
     * @return null|array
     */
    public function deleteSound($imgId)
    {
        try {
            if ($this->skillsId) {
                $query = $this->call($this->getUrl() . 'skills/' . $this->skillsId . '/sounds/' . $imgId, array(), 'DELETE');
                if (isset($query['result'])) {
                    return $query;
                }
                $this->setError($query);
                throw new Exception('YandexSounds::deleteSound() Error: Не удалось удалить картинку');
            }
            $this->setError('Не выбран навык');
            throw new Exception('YandexSounds::deleteSound() Error: Не выбран навык');
        } catch (Exception $e) {
            $this->logging($e, true);
            return null;
        }
    }

    /**
     * Удаление всех картинок
     * Если при удалении произошел сбой, то картинка останется.
     * Чтобы точно удалить все картинки лучше использовать грубое удаление
     *
     * Возвращает массив
     * - success - Количество успешно удаленных картинок
     * - fail - Количество не удаленных картинок
     *
     * @return array['success' => int, 'fail' => int]
     */
    public function deleteAllSounds()
    {
        $success = 0;
        $fail = 0;
        $sounds = $this->getLoadedSounds();
        foreach ($sounds as $sound) {
            $soundId = $sound['id'] ?? null;
            if ($soundId) {
                if ($this->deleteSound($soundId)) {
                    $success++;
                } else {
                    $fail++;
                }
                sleep(3);
            } else {
                $fail++;
            }
        }
        return ['success' => $success, 'fail' => $fail];
    }

    /**
     * Грубое удаление всех картинок
     */
    public function deleteAllSoundsIgnore()
    {
        do {
            $fail = $this->deleteAllSounds()['fail'];
        } while ($fail);
    }
}