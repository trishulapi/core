<?php


namespace TrishulApi\Core\File;

class MultipartFile{

    private string $name;
    private string $fullPath;
    private string $type;
    private string $tmpName;
    private $error;
    private int $size;

    public function __construct($name, $fullPath, $type, $tmpName, $error, $size)
    {
        $this->name = $name;
        $this->fullPath = $fullPath;
        $this->type = $type;
        $this->tmpName = $tmpName;
        $this->error = $error;
        $this->size = $size;
    }


    public function getName():string{
        return $this->name;
    }

    public function getFullPath():string{
        return $this->fullPath;
    }

    public function getType():string{
        return $this->type;
    }

    public function getTmpName():string{
        return $this->tmpName;
    }

    public function getError(){
        return $this->error;
    }

    public function getSize():int{
        return $this->size;
    }
}