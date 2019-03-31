<?php
namespace PoP\Engine;

class CacheManager extends CacheManagerBase
{
    public function __construct()
    {
        parent::__construct();
        
        CacheManager_Factory::setInstance($this);
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction(
            'popcms:init', 
            array($this, 'init')
        );
    }

    public function init()
    {

        // Allow the Theme to override the cache folder (eg: to add a custom folder after ir, eg: pop-cache/mesym/)
        if (!defined('POP_CACHE_DIR')) {
            define('POP_CACHE_DIR', WP_CONTENT_DIR.'/pop-cache');
        }
    }

    protected function getCacheBasedir()
    {
        return POP_CACHE_DIR;
    }
}

/**
 * Initialization
 */
new CacheManager();
