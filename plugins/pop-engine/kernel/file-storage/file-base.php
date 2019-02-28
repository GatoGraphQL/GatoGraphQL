<?php
namespace PoP\Engine\FileStorage;

abstract class FileBase
{
    public function getDir()
    {
        return '';
    }

    public function getFilename()
    {
        return '';
    }

    public function getFilepath()
    {
        return $this->getDir().'/'.$this->getFilename();
    }

    public function fileExists()
    {
        return file_exists($this->getFilepath());
    }
}
