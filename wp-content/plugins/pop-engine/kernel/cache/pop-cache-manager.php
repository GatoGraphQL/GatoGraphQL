<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_CACHE_EXT_HTML', '.html');
define ('POP_CACHETYPE_SETTINGS', 'settings');
define ('POP_CACHETYPE_DATASETTINGS', 'data-settings');
define ('POP_CACHETYPE_ATTS', 'atts');
define ('POP_CACHETYPE_CRAWLABLEITEMS', 'crawlable-items');
define ('POP_CACHETYPE_SITEMAPPING', 'sitemapping');
define ('POP_CACHETYPE_JSRESOURCES', 'js-resources');
define ('POP_CACHETYPE_JSBUNDLES', 'js-bundles');
define ('POP_CACHETYPE_JSBUNDLEGROUPS', 'js-bundlegroups');
define ('POP_CACHETYPE_CSSRESOURCES', 'css-resources');
define ('POP_CACHETYPE_CSSBUNDLES', 'css-bundles');
define ('POP_CACHETYPE_CSSBUNDLEGROUPS', 'css-bundlegroups');

class GD_Template_CacheManager extends GD_Template_CacheManagerBase {

	function __construct() {
		
		add_action('PoP:system-build', array($this, 'system_build'));
		add_action('init', array($this,'init'));
	}

	function init() {

		// Allow the Theme to override the cache folder (eg: to add a custom folder after ir, eg: pop-cache/mesym/)
		if (!defined ('POP_CACHE_DIR')) {
			define ('POP_CACHE_DIR', WP_CONTENT_DIR.'/pop-cache');
		}
	}

	function system_build() {

		$this->restore($this->get_cache_dir(POP_CACHETYPE_SETTINGS));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_DATASETTINGS));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_ATTS));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CRAWLABLEITEMS));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_SITEMAPPING));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSRESOURCES));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLES));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLEGROUPS));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CSSRESOURCES));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CSSBUNDLES));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CSSBUNDLEGROUPS));
	}

	function restore($cache_dir) {

		if (file_exists($cache_dir)) {
			
			// Delete all cached files
			foreach(glob("{$cache_dir}/*") as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
		}
		// else {

		// 	// Create the settings folder
		// 	@mkdir($cachesettings_dir, 0777, true);
		// }
	}

	protected function get_cache_basedir() {

		return POP_CACHE_DIR;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_cachemanager;
$gd_template_cachemanager = new GD_Template_CacheManager();
