<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_CACHE_EXT', '.json');
define ('POP_CACHETYPE_SETTINGS', 'settings');
define ('POP_CACHETYPE_DATASETTINGS', 'data-settings');
define ('POP_CACHETYPE_ATTS', 'atts');

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

		$this->restore($this->get_cache_dir(POP_CACHETYPE_SETTINGS));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_DATASETTINGS));
		$this->restore($this->get_cache_dir(POP_CACHETYPE_ATTS));
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

	private function get_cache_dir($type) {

		return POP_CACHE_DIR.'/'.$type;
	}

	private function get_file($filename, $type) {

		return $this->get_cache_dir($type).'/'.$filename.POP_CACHE_EXT;
	}

	function create_cache_dir($type) {

		$cache_dir = $this->get_cache_dir($type);
		if (!file_exists($cache_dir)) {
			
			// Create the settings folder
			@mkdir($cache_dir, 0777, true);
		}			
	}

	function get_cache($template_id, $type, $decode = false) {

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
				// Comment Leo 06/03/2017: do the same with all dynamic constants, so that we can generate a proper ETag also when retrieving the cached value
				$from = array(
					POP_CACHEPLACEHOLDER_UNIQUE_ID,
					POP_CACHEPLACEHOLDER_CURRENTTIMESTAMP,
					POP_CACHEPLACEHOLDER_RAND,
					POP_CACHEPLACEHOLDER_TIME,
				);
				$to = array(
					POP_CONSTANT_UNIQUE_ID,
					POP_CONSTANT_CURRENTTIMESTAMP,
					POP_CONSTANT_RAND,
					POP_CONSTANT_TIME,
				);
				$contents = str_replace($from, $to, $contents);

				if ($decode) {
					// Treat it as an array, not an object
					$contents_or_object = json_decode($contents, true);
				}
				else {
					$contents_or_object = $contents;
				}

				return $contents_or_object;
			}
		}

		return false;
	}

	function store_cache($template_id, $type, $contents_or_object, $encode = false) {

		global $gd_template_cacheprocessor_manager;
		if (!($processor = $gd_template_cacheprocessor_manager->get_processor($template_id))) {
		
			return false;
		}

		if ($filename = $processor->get_cache_filename($template_id)) {

			$file = $this->get_file($filename, $type);

			if ($encode) {
				$contents = json_encode($contents_or_object);
			}
			else {
				$contents = $contents_or_object;
			}
			$this->save_file($type, $file, $contents);
		}

		return false;
	}

	private function save_file($type, $file, $contents) {

		// Replace the uniqueId with the placeholder to keep the saved settings uniqueId-independent
		// Comment Leo 06/03/2017: do the same with all dynamic constants, so that we can generate a proper ETag also when retrieving the cached value
		$from = array(
			POP_CONSTANT_UNIQUE_ID,
			POP_CONSTANT_CURRENTTIMESTAMP,
			POP_CONSTANT_RAND,
			POP_CONSTANT_TIME,
		);
		$to = array(
			POP_CACHEPLACEHOLDER_UNIQUE_ID,
			POP_CACHEPLACEHOLDER_CURRENTTIMESTAMP,
			POP_CACHEPLACEHOLDER_RAND,
			POP_CACHEPLACEHOLDER_TIME,
		);
		$contents = str_replace($from, $to, $contents);

		// Make sure the directory exists
		$this->create_cache_dir($type);

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
