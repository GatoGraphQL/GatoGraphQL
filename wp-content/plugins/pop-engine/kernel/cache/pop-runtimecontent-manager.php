<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_CACHE_EXT_JS', '.js');
define ('POP_RUNTIMECONTENTTYPE_SETTINGS', 'settings');
define ('POP_RUNTIMECONTENTTYPE_SITEMAPPING', 'sitemapping');

class GD_Template_RuntimeContentManager extends GD_Template_CacheManagerBase {

	function init() {

		// Allow the Theme to override the cache folder (eg: to add a custom folder after ir, eg: pop-cache/mesym/)
		if (!defined ('POP_RUNTIMECONTENT_DIR')) {
			define ('POP_RUNTIMECONTENT_DIR', WP_CONTENT_DIR.'/pop-runtimecontent');
		}
		if (!defined ('POP_RUNTIMECONTENT_URL')) {
			define ('POP_RUNTIMECONTENT_URL', WP_CONTENT_URL.'/pop-runtimecontent');
		}
	}

	protected function get_default_extension() {

		return POP_CACHE_EXT_JS;
	}

	protected function get_cache_basedir() {

		// Add the version in the path, so it's easier to identify currently-needed files
		return POP_RUNTIMECONTENT_DIR.'/'.pop_version();
	}

	protected function get_cache_baseurl() {

		// Add the version in the path, so it's easier to identify currently-needed files
		// Allow to modify the domain, from Assets to Uploads CDN
		return apply_filters(
			'GD_Template_RuntimeContentManager:cache-baseurl',
			POP_RUNTIMECONTENT_URL.'/'.pop_version()
		);
	}

	protected function get_cache_url($type) {

		return $this->get_cache_baseurl().'/'.$type;
	}

	function get_file_url($template_id, $type, $ext = '') {			

		global $gd_template_cacheprocessor_manager;
		if (!($processor = $gd_template_cacheprocessor_manager->get_processor($template_id))) {
		
			return false;
		}			
		if ($filename = $processor->get_cache_filename($template_id)) {

			if (!$ext) {
				$ext = $this->get_default_extension();
			}
			
			return $this->get_cache_url($type).'/'.$filename.$ext;
		}

		return null;		
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_runtimecontentmanager;
$gd_template_runtimecontentmanager = new GD_Template_RuntimeContentManager();
