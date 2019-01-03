<?php
namespace PoP\Engine\FileStorage;

abstract class FileStorageBase {

	function save_file($file, $contents) {

		// Create the directory structure
		$this->create_dir(dirname($file));

		// Open the file, write content and close it
		$handle = fopen($file, "wb");
		$numbytes = fwrite($handle, $contents);
		fclose($handle);

		return $file;
	}

	private function create_dir($dir) {

		if (!file_exists($dir)) {

			// Create folder
			@mkdir($dir, 0777, true);			
		}
	}
}
