<?php
/**
 * User: MaxM18
 */

namespace alisa\api;

use Exception;

/**
 * Основной класс, отвечающий за взаимодействие с API. А так же он отправляет необходимые запросы.
 * Class YandexService
 * @package yandex\api
 * @property $error - Ошибки при работе с api
 */
class YandexService
{
    private $oauth = ''; // Токен. Необходим для авторизации
    const HEADER_RSS_XML = 'Content-Type: application/rss+xml';
    const HEADER_GZIP = 'Content-Encoding: gzip';
    const HEADER_AP_JSON = 'Content-Type: application/json';
    const HEADER_AP_XML = 'Content-Type: application/xml';
    const HEADER_FORM_DATA = 'Content-Type: multipart/form-data';
    private $error;

    /**
     * Происходит инициализация userId и токена, если они не проинициализированы
     * YandexTurboAPI constructor.
     *
     * @param null $oauth - токен для работы
     */
    public function __construct($oauth = null)
    {
        if ($oauth) {
            $this->setOAuth($oauth);
        }
    }

    /**
     * Отправка запроса.
     *
     * @param $url - На какой адресс будет выполняться запрос.
     * @param array $params (
     * 'file'=>'...', Указывать если подгружается файл (Сюда необходимо прописать пить до расположения файла на сервере)
     * 'post'=>'...', Указывать если необходимо отправить POST запрос
     * 'header'=>'...', Указать дополнительный заголовок.
     * ) - Можно не заполнять данное поле.
     * @param $customRequest - Кастомный запрос типа PUT DELETE или любой другой.
     *
     * @return array|null
     * @throws Exception
     */
    protected function sendRequire($url, $params = [], $customRequest = null)
    {
        $header = ['Authorization: OAuth ' . $this->oauth];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        if (isset($params['file']) && $params['file']) {
            if (is_file($params['file'])) {
                $mimeType = mime_content_type($params['file']);
                $file = new \CURLFile($params['file']);
                if ($mimeType) {
                    $file->setMimeType($mimeType);
                }
                curl_setopt($curl, CURLOPT_POSTFIELDS, ['file' => $file]);
                curl_setopt($curl, CURLOPT_POSTFIELDS, file_get_contents($params['file']));
                curl_setopt($curl, CURLOPT_POST, 1);
            } else {
                throw new Exception('YandexService::sendRequire() Error: Не удалось найти файл');
            }
        }
        if (isset($params['post']) && $params['post']) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params['post']);
            curl_setopt($curl, CURLOPT_POST, 1);
        }
        if (isset($params['header'])) {
            $header = array_merge($header, $params['header']);
        }
        if ($customRequest) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $customRequest);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($curl);
        curl_close($curl);
        if ($data) {
            return json_decode($data, true);
        }
        return $data;
    }

    /**
     * Отправка запроса на сервер.
     *
     * @param $url
     * @param $param
     * @param $customRequest
     * @return mixed|null
     */
    public function call($url, $param = [], $customRequest = null)
    {
        try {
            return $this->sendRequire($url, $param, $customRequest);
        } catch (Exception $e) {
            $this->logging($e);
            return null;
        }
    }

    /**
     * Установить токен.
     *
     * @param $oauth
     */
    public function setOauth($oauth)
    {
        $this->oauth = $oauth;
    }

    /**
     * Получить токен.
     *
     * @return string
     */
    public function getOauth()
    {
        return $this->oauth;
    }

    /**
     * Логирование ошибок.
     *
     * @param Exception $e - Исключение
     * @param bool $isError - Записывать ошибку в лог файл или нет
     * @param bool $isShow - Отобразить ошибку в консоли или нет
     */
    public function logging(Exception $e, $isError = true, $isShow = false)
    {
        $fileErrorDir = __DIR__ . '/error';
        if (!is_dir($fileErrorDir)) {
            mkdir($fileErrorDir);
        }
        $fileLog = fopen($fileErrorDir . '/YandexApi.log', 'a');
        fwrite($fileLog, $e->getMessage() . "\n" . $e->getTraceAsString());
        if ($isShow) {
            printf("%s\n", $e->getMessage());
        }
        if ($isError) {
            fwrite($fileLog, "\nРезультат:\n" . $this->getErrorToString() . "\n");
            if ($isShow) {
                printf("Результат:\n\t%s\n", $this->getErrorToString());
            }
        }
        fclose($fileLog);
    }

    /**
     * Добавить текст ошибки
     *
     * @param $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Получить текст ошибки
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Получить текст ошибки в текстовом формате
     *
     * @return string
     */
    public function getErrorToString()
    {
        return json_encode($this->error);
    }
}

