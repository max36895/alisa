<?php
/**
 * User: MaxM18
 */

namespace alisa\api;

use Exception;

require_once __DIR__ . '/YandexService.php';

/**
 * Класс для работы с api картинками для навыка.
 * Class YandexImages
 * @package yandex\api
 * @property string $skillId - Идентификатор вашего навыка.
 * @property string $IMAGE_TOKEN - Полученный токен.
 */
class YandexImages extends YandexService
{
    /**
     * Для получения токена перейти по ссылке
     * https://tech.yandex.ru/dialogs/alice/doc/resource-upload-docpage/ - Документация
     * https://oauth.yandex.ru/verification_code#access_token=AQAAAAAPOgSRAAT7o34vRz8grkOXph2wy8YqFjc&token_type=bearer&expires_in=31536000 - Получение токена
     */
    const IMAGE_TOKEN = '<Токен, который мы получили при регистрации>';

    /**
     * Идентификатор навыка. Можно получить в запросе навыка(Блок session поле skill_id). Или из url навыка в панели для разработчиков.
     * @var string null
     */
    public $skillsId;

    public final function __construct($oauth = null)
    {
        if (!$oauth) {
            $oauth = self::IMAGE_TOKEN;
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
    public function setImageToken($token)
    {
        $this->setOauth($token);
    }

    /**
     * Получить токен.
     *
     * @return string
     */
    public function getImageToken()
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
            if (isset($query['images']['quota'])) {
                return $query['images']['quota'];
            }
            $this->setError($query);
            throw new Exception('YandexImages::isStatus() Error: Не удалось проверить занятое место');
        } catch (Exception $e) {
            $this->logging($e, true);
            return null;
        }
    }

    /**
     * Загрузка изображения из интернета
     *
     * Вернет массив
     * - id - Идентификатор изображения
     * - origUrl - Адрес изображения.
     *
     * @param $url - Адресс картики из интернета
     *
     * @return null|array['id' => string, 'origUrl' => string]
     */
    public function downloadImageUrl($url)
    {
        try {
            if ($this->skillsId) {
                $query = $this->call($this->getUrl() . 'skills/' . $this->skillsId . '/images', array('header' => array(self::HEADER_AP_JSON), 'post' => json_encode(array('url' => $url))));
                if (isset($query['image']['id'])) {
                    return $query['image'];
                } else {
                    $this->setError($query);
                    throw new Exception('YandexImages::downloadImageUrl() Error: Не удалось загрузить изображение с сайта: ' . $url);
                }
            }
            $this->setError('Не выбран навык');
            throw new Exception('YandexImages::downloadImageUrl() Error: Не выбран навык');
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
     * @param $img - Расположение картинки на сервере
     *
     * @return null|array['id' => string, 'origUrl' => string]
     */
    public function downloadImageFile($img)
    {
        try {
            if ($this->skillsId) {
                $query = $this->call($this->getUrl() . 'skills/' . $this->skillsId . '/images', array('header' => array(self::HEADER_FORM_DATA), 'file' => $img));
                if (isset($query['image']['id'])) {
                    return $query['image'];
                } else {
                    $this->setError($query);
                    throw new Exception('YandexImages::downloadImageFile() Error: Не удалось загрузить изображение: ' . $img);
                }
            }
            $this->setError('Не выбран навык');
            throw new Exception('YandexImages::downloadImageFile() Error: Не выбран навык');
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
    public function getLoadedImages()
    {
        try {
            if ($this->skillsId) {
                $query = $this->call($this->getUrl() . 'skills/' . $this->skillsId . '/images');
                if (isset($query['images'])) {
                    return $query['images'];
                } else {
                    $this->setError($query);
                    throw new Exception('YandexImages::getLoadedImages() Error: Не удалось получить список загруженных сообщений');
                }
            }
            $this->setError('Не выбран навык');
            throw new Exception('YandexImages::getLoadedImages() Error: Не выбран навык');
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
    public function deleteImage($imgId)
    {
        try {
            if ($this->skillsId) {
                $query = $this->call($this->getUrl() . 'skills/' . $this->skillsId . '/images/' . $imgId, array(), 'DELETE');
                if (isset($query['result'])) {
                    return $query;
                }
                $this->setError($query);
                throw new Exception('YandexImages::deleteImage() Error: Не удалось удалить картинку');
            }
            $this->setError('Не выбран навык');
            throw new Exception('YandexImages::deleteImage() Error: Не выбран навык');
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
    public function deleteAllImage()
    {
        $success = 0;
        $fail = 0;
        $images = $this->getLoadedImages();
        foreach ($images as $image) {
            $imageId = $image['id'] ?? null;
            if ($imageId) {
                if ($this->deleteImage($imageId)) {
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
    public function deleteAllImageIgnore()
    {
        do {
            $fail = $this->deleteAllImage()['fail'];
        } while ($fail);
    }
}