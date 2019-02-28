<?php
namespace PoP\Engine;

class MemoryManager extends CacheManagerBase
{
    public function __construct()
    {
        parent::__construct();
        
        MemoryManager_Factory::setInstance($this);
    }

    public function init()
    {

        // Allow the Theme to override the cache folder (eg: to add a custom folder after ir, eg: pop-cache/mesym/)
        if (!defined('POP_MEMORY_DIR')) {
            define('POP_MEMORY_DIR', WP_CONTENT_DIR.'/pop-memory');
        }
    }

    protected function getCacheBasedir()
    {

        // Add the version in the path, so it's easier to identify currently-needed files
        return POP_MEMORY_DIR.'/'.popVersion();
    }
}

/**
 * Initialization
 */
new MemoryManager();
