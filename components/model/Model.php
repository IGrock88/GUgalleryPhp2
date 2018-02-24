<?php

namespace components\model;


use components\database\DB;
use components\gallery\ImgHandler;

class Model
{
    private $imageHandler;

    public function __construct()
    {
        $this->imageHandler = new ImgHandler(DB::getInstance());
        $this->userRequest();
    }

    public function getImages(){
        return $this->imageHandler->getImages();
    }

    public function getImageById($idImage){
        $result = $this->imageHandler->getImageByID($idImage);
        return $result;
    }

    public function userRequest(){
        if($_POST['uploadFile']) {
            $this->imageHandler->uploadFileToServer(GALLERY_DB_TABLE);
        }
        if ($_POST['deleteAll']){
            $this->imageHandler->deleteAllImg(GALLERY_DB_TABLE);
        }
    }



}