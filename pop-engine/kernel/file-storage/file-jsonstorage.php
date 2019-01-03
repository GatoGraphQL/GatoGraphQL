<?php
namespace PoP\Engine\FileStorage;

class FileJSONStorage extends FileStorageBase {

	function __construct() {

		FileJSONStorage_Factory::set_instance($this);
	}

	function save($file, $contents) {

		// Encode it and save it
		$json = json_encode($contents);
		$this->save_file($file, $json);
	}

	function get($file) {

		if (file_exists($file)) {

			// Return the file contents
			$contents = file_get_contents($file);
			return json_decode($contents, true);
		}

		return array();
	}

	function delete($file) {

		if (file_exists($file)) {

			unlink($file);
			return true;
		}

		return false;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new FileJSONStorage();
