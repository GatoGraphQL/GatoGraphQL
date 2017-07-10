<?php
class PoP_Frontend_ConversionStorage {

	private function get_dir(){

		return POP_FRONTENDENGINE_CONTENT_DIR;
	}

	function get_filepath($filename){

		return $this->get_dir().'/'.$filename;
	}

	function get_file(){

		return $this->get_filepath('css-to-style-mapping.json');
	}

	function save($contents){

		// Create the directory structure
		$this->create_dir();

		// Encode it and save it
		$json = json_encode($contents);
		$this->save_file($this->get_file(), $json);
	}

	function get(){

		$file = $this->get_file();
		if (file_exists($file)) {

			// Return the file contents
			$contents = file_get_contents($file);
			return json_decode($contents, true);
		}

		return array();
	}

	private function save_file($file, $contents) {

		// Open the file, write content and close it
		$handle = fopen($file, "wb");
		
		// Possibly because the $contents are an encoded JSON, the line below produces an error, so commented the PHP_EOL bit
		// $numbytes = fwrite($handle, implode(PHP_EOL, $contents));
		$numbytes = fwrite($handle, $contents);
		fclose($handle);

		return $file;
	}

	private function create_dir(){

		$dir = $this->get_dir();
		if (!file_exists($dir)) {

			// Create folder
			@mkdir($dir, 0777, true);			
		}
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_frontend_conversionstorage;
$pop_frontend_conversionstorage = new PoP_Frontend_ConversionStorage();