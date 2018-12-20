<?php

define ('POP_CACHETYPE_IMMUTABLESETTINGS', 'static-settings');
define ('POP_CACHETYPE_STATEFULSETTINGS', 'stateful-settings');
define ('POP_CACHETYPE_STATICDATAPROPERTIES', 'static-data-properties');
define ('POP_CACHETYPE_STATEFULDATAPROPERTIES', 'stateful-data-properties');
define ('POP_CACHETYPE_PROPS', 'props');

class PoP_Module_CacheManager extends PoP_Module_CacheManagerBase {

	function __construct() {

		parent::__construct();
		
		add_action('init', array($this, 'init'));
	}

	function init() {

		// Allow the Theme to override the cache folder (eg: to add a custom folder after ir, eg: pop-cache/mesym/)
		if (!defined ('POP_CACHE_DIR')) {
			define ('POP_CACHE_DIR', WP_CONTENT_DIR.'/pop-cache');
		}
	}

	protected function get_cache_basedir() {

		return POP_CACHE_DIR;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_module_cachemanager;
$pop_module_cachemanager = new PoP_Module_CacheManager();
