<?php
use PoP\FileStore\Facades\JSONFileStoreFacade;

class PoP_WebPlatform_ResourceLoaderMappingManager
{
    protected $mapping;
    protected $initialized;

    public function __construct()
    {
        $this->mapping = array();
        $this->initialized = false;
    }

    public function init()
    {

        // Allows lazy init
        if (!$this->initialized) {
            $this->initialized = true;

            // Get the inner variable from the cache, if it exists
            global $pop_webplatform_resourceloader_mappingfile;
            $this->mapping = JSONFileStoreFacade::getInstance()->get($pop_webplatform_resourceloader_mappingfile);
        }
    }

    public function getMapping()
    {
        $this->init();
        return $this->mapping;
    }
}

/**
 * Initialization
 */
global $pop_webplatform_resourceloader_mappingmanager;
$pop_webplatform_resourceloader_mappingmanager = new PoP_WebPlatform_ResourceLoaderMappingManager();
