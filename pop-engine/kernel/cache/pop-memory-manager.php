<?php
namespace PoP\Engine;

class MemoryManager extends CacheManagerBase {

	function __construct() {

		parent::__construct();
		
    	MemoryManager_Factory::set_instance($this);    
	}

	function init() {

		// Allow the Theme to override the cache folder (eg: to add a custom folder after ir, eg: pop-cache/mesym/)
		if (!defined ('POP_MEMORY_DIR')) {
			define ('POP_MEMORY_DIR', WP_CONTENT_DIR.'/pop-memory');
		}
	}

	protected function get_cache_basedir() {

		// Add the version in the path, so it's easier to identify currently-needed files
		return POP_MEMORY_DIR.'/'.pop_version();
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new MemoryManager();
