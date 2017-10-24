<?php
class PoP_Frontend_FileJSONStorage extends PoP_Frontend_FileStorage {

	function save($file, $contents) {

		// // Create the directory structure
		// $this->create_dir();

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
global $pop_frontend_filejsonstorage;
$pop_frontend_filejsonstorage = new PoP_Frontend_FileJSONStorage();
