<?php
class PoP_CDNCore_Manager {

	private function get_dir(){

		return POP_CDNCORE_ASSETDESTINATION_DIR;
	}
	private function get_url(){

		return POP_CDNCORE_ASSETDESTINATION_URI;
	}

	function get_filepath($filename){

		return $this->get_dir().'/'.$filename;
	}

	function get_fileurl($filename){

		return $this->get_url().'/'.$filename;
	}

	function generate_files(){

		global $pop_cdncore_job_manager;

		// Create the directory structure
		$this->create_dir();

		// CDNCore Thumbprints .js file
		$contents = $pop_cdncore_job_manager->render();
		$this->save_file($this->get_filepath('cdn-config.js'), $contents);
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
global $pop_cdncore_manager;
$pop_cdncore_manager = new PoP_CDNCore_Manager();