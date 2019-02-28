<?php
namespace PoP\Engine\FileStorage;

class FileStorage extends FileStorageBase
{
    public function __construct()
    {
        FileStorage_Factory::setInstance($this);
    }
}
    
/**
 * Initialize
 */
new FileStorage();
