<?php
namespace PoP\Engine\FileStorage;

abstract class FileObjectBase extends FileBase
{
    public function delete()
    {
        FileJSONStorage_Factory::getInstance()->delete($this->getFilepath());
    }

    public function save($object)
    {
        FileJSONStorage_Factory::getInstance()->save($this->getFilepath(), $object);
    }

    public function get()
    {
        FileJSONStorage_Factory::getInstance()->get($this->getFilepath());
    }
}
