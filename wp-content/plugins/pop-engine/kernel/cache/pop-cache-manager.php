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

define ('POP_CACHETYPE_JSRESOURCES_NORMAL', 'js-resources-normal');
define ('POP_CACHETYPE_JSRESOURCES_VENDOR', 'js-resources-vendor');
define ('POP_CACHETYPE_JSRESOURCES_DYNAMIC', 'js-resources-dynamic');
define ('POP_CACHETYPE_JSRESOURCES_TEMPLATE', 'js-resources-template');
define ('POP_CACHETYPE_JSBUNDLES_NORMAL', 'js-bundles-normal');
define ('POP_CACHETYPE_JSBUNDLES_VENDOR', 'js-bundles-vendor');
define ('POP_CACHETYPE_JSBUNDLES_DYNAMIC', 'js-bundles-dynamic');
define ('POP_CACHETYPE_JSBUNDLES_TEMPLATE', 'js-bundles-template');
define ('POP_CACHETYPE_JSBUNDLEGROUPS_NORMAL', 'js-bundlegroups-normal');
define ('POP_CACHETYPE_JSBUNDLEGROUPS_VENDOR', 'js-bundlegroups-vendor');
define ('POP_CACHETYPE_JSBUNDLEGROUPS_DYNAMIC', 'js-bundlegroups-dynamic');
define ('POP_CACHETYPE_JSBUNDLEGROUPS_TEMPLATE', 'js-bundlegroups-template');
define ('POP_CACHETYPE_CSSRESOURCES_NORMAL', 'css-resources-normal');
define ('POP_CACHETYPE_CSSRESOURCES_VENDOR', 'css-resources-vendor');
define ('POP_CACHETYPE_CSSRESOURCES_DYNAMIC', 'css-resources-dynamic');
define ('POP_CACHETYPE_CSSBUNDLES_NORMAL', 'css-bundles-normal');
define ('POP_CACHETYPE_CSSBUNDLES_VENDOR', 'css-bundles-vendor');
define ('POP_CACHETYPE_CSSBUNDLES_DYNAMIC', 'css-bundles-dynamic');
define ('POP_CACHETYPE_CSSBUNDLEGROUPS_NORMAL', 'css-bundlegroups-normal');
define ('POP_CACHETYPE_CSSBUNDLEGROUPS_VENDOR', 'css-bundlegroups-vendor');
define ('POP_CACHETYPE_CSSBUNDLEGROUPS_DYNAMIC', 'css-bundlegroups-dynamic');

define ('POP_CACHETYPE_JSRESOURCES_IMMEDIATE', 'js-resources-immediate');
define ('POP_CACHETYPE_JSBUNDLES_IMMEDIATE', 'js-bundles-immediate');
define ('POP_CACHETYPE_JSBUNDLEGROUP_IMMEDIATE', 'js-bundlegroup-immediate');
define ('POP_CACHETYPE_JSRESOURCES_ASYNC', 'js-resources-async');
define ('POP_CACHETYPE_JSBUNDLES_ASYNC', 'js-bundles-async');
define ('POP_CACHETYPE_JSBUNDLEGROUP_ASYNC', 'js-bundlegroup-async');
define ('POP_CACHETYPE_JSRESOURCES_DEFER', 'js-resources-defer');
define ('POP_CACHETYPE_JSBUNDLES_DEFER', 'js-bundles-defer');
define ('POP_CACHETYPE_JSBUNDLEGROUP_DEFER', 'js-bundlegroup-defer');

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
		
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSRESOURCES_NORMAL));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSRESOURCES_VENDOR));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSRESOURCES_DYNAMIC));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSRESOURCES_TEMPLATE));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLES_NORMAL));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLES_VENDOR));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLES_DYNAMIC));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLES_TEMPLATE));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLEGROUPS_NORMAL));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLEGROUPS_VENDOR));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLEGROUPS_DYNAMIC));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLEGROUPS_TEMPLATE));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CSSRESOURCES_NORMAL));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CSSRESOURCES_VENDOR));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CSSRESOURCES_DYNAMIC));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CSSBUNDLES_NORMAL));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CSSBUNDLES_VENDOR));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CSSBUNDLES_DYNAMIC));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CSSBUNDLEGROUPS_NORMAL));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CSSBUNDLEGROUPS_VENDOR));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_CSSBUNDLEGROUPS_DYNAMIC));

		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSRESOURCES_IMMEDIATE));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLES_IMMEDIATE));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLEGROUP_IMMEDIATE));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSRESOURCES_ASYNC));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLES_ASYNC));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLEGROUP_ASYNC));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSRESOURCES_DEFER));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLES_DEFER));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_JSBUNDLEGROUP_DEFER));
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
