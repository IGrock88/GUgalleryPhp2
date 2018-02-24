<?php

namespace components\gallery;

use components\database\DB;
use Gumlet\ImageResize;

class ImgHandler
{
    private $images = [];
    private $allowedImageTypes = ['image/gif', 'image/png', 'image/jpeg'];
    private $allowedSize = 3145728; // 3mb
    private $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function getImages(): array
    {
        $this->loadImageFromDB(GALLERY_DB_TABLE);
        return $this->images;
    }

    public function getImageByID($idImage)
    {
        $result = null;
        try {
            $this->db->connect();
            $result = $this->db->select(GALLERY_DB_TABLE, "*", "idImg=$idImage");
            $result = new Img($result[0]['idImg'], $result[0]['smallPath'], $result[0]['bigPath']);
        } catch (mysqli_sql_exception $e) {
            echo $e->getMessage() . "ошибка загрузки из бд";
        } finally {
            $this->db->close();
        }
        return $result;
    }

    public function uploadFileToServer($table)
    {
        $newName = uniqid('img_') . "." . pathinfo($_FILES['imges']['name'])['extension'];
        $bigPath = BIG_IMG_DIR . $newName;
        $smallPath = SMALL_IMG_DIR . $newName;

        if ($_FILES['imges']['size'] > $this->allowedSize) {
            echo "Слишком большой размер файла<br>";
            return -1;
        }
        if (!in_array($_FILES['imges']['type'], $this->allowedImageTypes)) {
            echo "Не верный формат файла";
            return -1;
        }

        if ($this->insertInDB($bigPath, $smallPath, $table)) {
            if (move_uploaded_file($_FILES['imges']['tmp_name'], $bigPath)) {
                echo "Файл загружен успешно <br>";
                $this->minifyImage($bigPath, $smallPath);
            } else {
                echo "Ошибка загрузки файла <br>";
            }
        }
    }

    public function deleteAllImg($table)
    {
        try {
            $this->db->connect();
            if ($this->db->delete($table)) {
                $this->clearDir(BIG_IMG_DIR);
                $this->clearDir(SMALL_IMG_DIR);
            }
        } catch (mysqli_sql_exception $e) {
            echo $e->getMessage() . "ошибка удаления из бд";
        } finally {
            $this->db->close();
        }
    }


    private function loadImageFromDB($table)
    {
        try {
            $this->db->connect();
            $result = $this->db->select($table);
            $this->addImage($result);
        } catch (mysqli_sql_exception $e) {
            echo $e->getMessage() . "ошибка загрузки из бд";
        } finally {
            $this->db->close();
        }
    }

    private function minifyImage($bigPath, $smallPath)
    {
        $image = new ImageResize($bigPath);
        $image->resize(200, 110, $allow_enlarge = True);
        $image->save($smallPath);
    }

    private function addImage($rowResult)
    {
        foreach ($rowResult as $key => $image) {
            $this->images[$image['idImg']] = new Img($image['idImg'], $image['smallPath'], $image['bigPath']);
        }
    }

    private function insertInDB($bigPath, $smallPath, $table)
    {
        $result = [];
        try {
            $this->db->connect();
            $result = $this->db->insert($table, "bigPath, smallPath", [$bigPath, $smallPath]);
        } catch (mysqli_sql_exception $e) {
            echo $e->getMessage() . "ошибка добавления в бд";
        } finally {
            $this->db->close();
        }
        return $result;
    }

    private function clearDir($dir)
    {
        $list = scandir($dir);
        unset($list[0], $list[1]);
        $list = array_values($list);

        foreach ($list as $file) {
            if (is_dir($dir . $file)) {
                clear_dir($dir . $file . '/');
                rmdir($dir . $file);
            } else {
                unlink($dir . $file);
            }
        }
    }
}