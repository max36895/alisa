<?php
/**
 * Created by PhpStorm.
 * User: max18
 * Date: 25.06.2019
 * Time: 8:41
 */

namespace bot\components\standard;
/**
 *
 * @property string $imgDir      - директория картинки
 * @property string $title       - заголовок для картинки
 * @property string $description - описание для картинки
 * @property array $imagesList   - список картинок
 **/
class Image
{
    public $title;
    public $imgDir;
    public $description;

    public $imagesList;

    public function __construct()
    {
        $this->clearImagesList();
    }

    public final function countImagesList(): int
    {
        if (is_array($this->imagesList)) {
            return count($this->imagesList);
        }
        return 0;
    }

    public final function clearImagesList(): void
    {
        $this->imagesList = [];
    }

    /**
     * Добавить картинку в коллекцию
     *
     * @param string $imageDir
     * @param string $title
     * @param string $description
     *
     * @return array
     */
    public function addImagesList($imageDir, $title, $description): array
    {
        return [
            'image_dir' => $imageDir,
            'title' => $title,
            'description' => $description
        ];
    }
}