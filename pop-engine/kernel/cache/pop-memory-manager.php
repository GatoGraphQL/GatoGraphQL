<?php

class PoP_Module_MemoryManager extends PoP_Module_CacheManagerBase {

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
global $pop_module_memorymanager;
$pop_module_memorymanager = new PoP_Module_MemoryManager();
