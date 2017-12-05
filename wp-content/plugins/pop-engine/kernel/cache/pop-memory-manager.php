<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_MEMORYTYPE_NONCRITICALRESOURCES', 'noncritical-resources');
define ('POP_MEMORYTYPE_DYNAMICTEMPLATERESOURCES', 'dynamic-template-resources');

class GD_Template_MemoryManager extends GD_Template_CacheManagerBase {

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
global $gd_template_memorymanager;
$gd_template_memorymanager = new GD_Template_MemoryManager();
