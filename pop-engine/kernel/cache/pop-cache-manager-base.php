<?php

define ('POP_CACHE_EXT_JSON', '.json');
define ('POP_CACHE_EXT_HTML', '.html');

class PoP_Module_CacheManagerBase {

	function __construct() {

		// When a plugin is activated/deactivated, ANY plugin, delete the corresponding cached files
		// This is particularly important for the MEMORY, since we can't set by constants to not use it
		add_action(
			'activate_plugin',
			array($this, 'delete_cache')
		);
		add_action(
			'deactivate_plugin',
			array($this, 'delete_cache')
		);
	}

	function delete_cache() {

		if (file_exists($this->get_cache_basedir())) {
			
			deleteDir($this->get_cache_basedir());
		}
	}

	protected function get_cache_basedir() {

		return '';
	}

	protected function get_cache_dir($type) {

		return $this->get_cache_basedir().'/'.$type;
	}

	protected function get_default_extension() {

		return POP_CACHE_EXT_JSON;
	}

	function get_file_by_model_instance($type, $ext = '') {

		$model_instance_id = PoP_Module_ModelInstanceProcessor_Utils::get_model_instance_id();
		return $this->get_file($model_instance_id, $type, $ext);
	}

	function get_file($filename, $type, $ext = '') {

		// $filename = $this->get_filename($filename);

		if (!$ext) {
			$ext = $this->get_default_extension();
		}

		return $this->get_cache_dir($type).'/'.$filename.$ext;
	}

	// private function get_filename($model_instance_id) {

	// 	// Do not start with a number (just in case)
	// 	return 'c'.$model_instance_id;
	// }
	private function get_filename($filename) {

		return $filename;
	}

	function create_cache_dir($type) {

		$cache_dir = $this->get_cache_dir($type);
		if (!file_exists($cache_dir)) {
			
			// Create the settings folder
			@mkdir($cache_dir, 0777, true);
		}			
	}

	function get_savefile_cache_replacements() {

		return array(
			'from' => array(
				POP_CONSTANT_UNIQUE_ID,
				POP_CONSTANT_CURRENTTIMESTAMP,
				POP_CONSTANT_RAND,
				POP_CONSTANT_TIME,
			),
			'to' => array(
				POP_CACHEPLACEHOLDER_UNIQUE_ID,
				POP_CACHEPLACEHOLDER_CURRENTTIMESTAMP,
				POP_CACHEPLACEHOLDER_RAND,
				POP_CACHEPLACEHOLDER_TIME,
			),
		);
	}

	function get_loadfile_cache_replacements() {

		$savefile_replacements = $this->get_savefile_cache_replacements();
		return array(
			'from' => $savefile_replacements['to'],
			'to' => $savefile_replacements['from'],
		);
	}

	function cache_exists_by_model_instance($type, $ext = '') {

		$model_instance_id = PoP_Module_ModelInstanceProcessor_Utils::get_model_instance_id();
		return $this->cache_exists($model_instance_id, $type, $ext);
	}

	function cache_exists($filename, $type, $ext = '') {

		$file = $this->get_file($filename, $type, $ext);
		return file_exists($file);
	}

	function get_cache_by_model_instance($type, $decode = false, $ext = '') {

		$model_instance_id = PoP_Module_ModelInstanceProcessor_Utils::get_model_instance_id();
		return $this->get_cache($model_instance_id, $type, $decode, $ext);
	}

	function get_cache($filename, $type, $decode = false, $ext = '') {

		$file = $this->get_file($filename, $type, $ext);
		if (file_exists($file)) {

			// Return the file contents
			$contents = file_get_contents($file);

			// Replace the placeholder for the uniqueId with the current uniqueId
			// Comment Leo 06/03/2017: do the same with all dynamic constants, so that we can generate a proper ETag also when retrieving the cached value
			if ($replacements = $this->get_loadfile_cache_replacements()) {

				$from = $replacements['from'];
				$to = $replacements['to'];
				if ($from && $to) {

					$contents = str_replace($from, $to, $contents);
				}
			}

			if ($decode) {
				// Treat it as an array, not an object
				$contents_or_object = json_decode($contents, true);
			}
			else {
				$contents_or_object = $contents;
			}

			return $contents_or_object;
		}

		return false;
	}

	function store_cache_by_model_instance($type, $contents_or_object, $encode = false, $ext = '') {

		$model_instance_id = PoP_Module_ModelInstanceProcessor_Utils::get_model_instance_id();
		return $this->store_cache($model_instance_id, $type, $contents_or_object, $encode, $ext);
	}

	function store_cache($filename, $type, $contents_or_object, $encode = false, $ext = '') {

		$file = $this->get_file($filename, $type, $ext);

		if ($encode) {
			$contents = json_encode($contents_or_object);
		}
		else {
			$contents = $contents_or_object;
		}
		
		return $this->save_file($type, $file, $contents);
	}

	private function save_file($type, $file, $contents) {

		// Replace the uniqueId with the placeholder to keep the saved settings uniqueId-independent
		// Comment Leo 06/03/2017: do the same with all dynamic constants, so that we can generate a proper ETag also when retrieving the cached value
		if ($replacements = $this->get_savefile_cache_replacements()) {

			$from = $replacements['from'];
			$to = $replacements['to'];
			if ($from && $to) {

				$contents = str_replace($from, $to, $contents);
			}
		}

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
