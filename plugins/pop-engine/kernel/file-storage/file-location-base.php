<?php
namespace PoP\Engine\FileStorage;

abstract class FileLocationBase extends FileBase
{
    public function getUrl()
    {
        return '';
    }

    public function getFileurl()
    {
        return $this->getUrl().'/'.$this->getFilename();
    }
}
