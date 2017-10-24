<?php
class PoP_Frontend_FileStorage {

	function save_file($file, $contents) {

		// Create the directory structure
		$this->create_dir(dirname($file));

		// Open the file, write content and close it
		$handle = fopen($file, "wb");
		
		// Possibly because the $contents are an encoded JSON, the line below produces an error, so commented the PHP_EOL bit
		// $numbytes = fwrite($handle, implode(PHP_EOL, $contents));
		$numbytes = fwrite($handle, $contents);
		fclose($handle);

		return $file;
	}

	private function create_dir($dir) {

		// $dir = $this->get_dir();
		if (!file_exists($dir)) {

			// Create folder
			@mkdir($dir, 0777, true);			
		}
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_frontend_filestorage;
$pop_frontend_filestorage = new PoP_Frontend_FileStorage();
