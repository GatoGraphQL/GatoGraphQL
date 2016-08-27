<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_CACHE_EXT', '.json');
define ('POP_CACHETYPE_SETTINGS', 'settings');

class GD_Template_CacheManager {

	function __construct() {
		
		add_action('PoP:install', array($this,'install'));
		add_action('init', array($this,'init'));
	}

	function init() {

		// Allow the Theme to override the cache folder (eg: to add a custom folder after ir, eg: pop-cache/mesym/)
		if (!defined ('POP_CACHE_DIR')) {
			define ('POP_CACHE_DIR', WP_CONTENT_DIR.'/pop-cache');
		}
	}

	function install() {

		$cachesettings_dir = POP_CACHE_DIR.'/'.POP_CACHETYPE_SETTINGS;

		if (file_exists($cachesettings_dir)) {
			
			// Delete all cached files
			foreach(glob("{$cachesettings_dir}/*") as $file) {
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

	function get_cache_dir() {

		return POP_CACHE_DIR.'/'.POP_CACHETYPE_SETTINGS;
	}

	function create_cache_dir() {

		$cachesettings_dir = $this->get_cache_dir();
		if (!file_exists($cachesettings_dir)) {
			
			// Create the settings folder
			@mkdir($cachesettings_dir, 0777, true);
		}			
	}

	function get_cache($template_id, $type) {

		global $gd_template_cacheprocessor_manager;
		if (!($processor = $gd_template_cacheprocessor_manager->get_processor($template_id))) {
		
			return false;
		}			
		if ($filename = $processor->get_cache_filename($template_id)) {

			$file = $this->get_file($filename, $type);
			if (file_exists($file)) {

				// Return the file contents
				$contents = file_get_contents($file);

				// Replace the placeholder for the uniqueId with the current uniqueId
				$contents = str_replace(POP_CONSTANT_UNIQUE_ID_CACHEPLACEHOLDER, POP_CONSTANT_UNIQUE_ID, $contents);

				return $contents;
			}
		}

		return false;
	}

	function store_cache($template_id, $type, $contents) {

		global $gd_template_cacheprocessor_manager;
		if (!($processor = $gd_template_cacheprocessor_manager->get_processor($template_id))) {
		
			return false;
		}
		if ($filename = $processor->get_cache_filename($template_id)) {

			$file = $this->get_file($filename, $type);
			$this->save_file($file, $contents);
		}

		return false;
	}

	private function get_file($filename, $type) {

		return POP_CACHE_DIR.'/'.$type.'/'.$filename.POP_CACHE_EXT;
	}

	private function save_file($file, $contents) {

		// Replace the uniqueId with the placeholder to keep the saved settings uniqueId-independent
		$contents = str_replace(POP_CONSTANT_UNIQUE_ID, POP_CONSTANT_UNIQUE_ID_CACHEPLACEHOLDER, $contents);

		// Make sure the directory exists
		$this->create_cache_dir();

		// Open the file, write content and close it
		$handle = fopen($file, "wb");
		
		// Possibly because the $contents are an encoded JSON, the line below produces an error, so commented the PHP_EOL bit
		// $numbytes = fwrite($handle, implode(PHP_EOL, $contents));
		$numbytes = fwrite($handle, $contents);
		fclose($handle);

		return $file;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_cachemanager;
$gd_template_cachemanager = new GD_Template_CacheManager();
