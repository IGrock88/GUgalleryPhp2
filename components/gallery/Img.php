<?php

namespace components\gallery;

class Img
{
    private $idImg;
    private $smallPath;
    private $bigPath;

    public function __construct($idImg, $smallPath, $bigPath)
    {
        $this->idImg = $idImg;
        $this->smallPath = $smallPath;
        $this->bigPath = $bigPath;
    }

    public function getIdImg()
    {
        return $this->idImg;
    }

    public function getSmallPath()
    {
        return $this->smallPath;
    }

    public function getBigPath()
    {
        return $this->bigPath;
    }
}