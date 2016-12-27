<?php
class PoP_ServiceWorkers_Manager {

	private $scope;

	function __construct() {
		$this->scope = site_url('/', 'relative');
	}

	private function get_dir(){

		return POP_SERVICEWORKERS_ASSETDESTINATION_DIR;
	}
	private function get_url(){

		return POP_SERVICEWORKERS_ASSETDESTINATION_URI;
	}
	function get_dependencies_foldername(){

		return 'lib';
	}
	

	function get_filepath($filename){

		return $this->get_dir().'/'.$filename;
	}

	function get_fileurl($filename){

		return $this->get_url().'/'.$filename;
	}

	private function sw_registrar() {
        $contents = file_get_contents(POP_SERVICEWORKERS_ASSETS_DIR.'/js/sw-registrar.js');
        $contents = str_replace('$enabledSw', json_encode($this->json_for_sw_registrations()), $contents);
        return $contents;
    }

	private function htaccess() {
        $contents = file_get_contents(POP_SERVICEWORKERS_ASSETS_DIR.'/.htaccess');
        $contents = str_replace('$scope', $this->scope, $contents);
        return $contents;
    }

    private function json_for_sw_registrations() {
        
        return array(
        	array(
	        	'scope' => $this->scope,
	        	'url' => $this->get_fileurl('service-worker.js')
	        )
        );
    }

	function generate_files(){

		global $pop_serviceworkers_job_manager;

		// Create the directory structure
		$this->create_dir();

		// File to register the Service Worker
		$this->save_file($this->get_filepath('sw-registrar.js'), $this->sw_registrar());

		// Service Worker .js file
		$sw_contents = $pop_serviceworkers_job_manager->render_sw($this->scope);
		$this->save_file($this->get_filepath('service-worker.js'), $sw_contents);

		// Copy the dependencies
		$dependencies = $pop_serviceworkers_job_manager->get_dependencies($this->scope);
		foreach ($dependencies as $dependency) {
			copy($dependency, $this->get_filepath($this->get_dependencies_foldername().'/'.basename($dependency)));
		}

		// Create the .htaccess file to allow access to the scope (/) served by a file in another folder
		$this->save_file($this->get_filepath('.htaccess'), $this->htaccess());
		// copy(POP_SERVICEWORKERS_ASSETS_DIR.'/.htaccess', $this->get_filepath('.htaccess'));
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
		$dir .= '/'.$this->get_dependencies_foldername();
		if (!file_exists($dir)) {

			// Also the dependencies folder
			@mkdir($dir, 0777, true);			
		}
		// else {
			
		// 	// Delete all .js files
		// 	foreach(glob("{$dir}/*") as $file) {
		// 		if (is_file($file)) {
		// 			unlink($file);
		// 		}
		// 	}
		// }

		// // Copy the .htaccess file, which will be needed for service-worker.js
		// copy(POP_SERVICEWORKERS_ASSETS_DIR.'/.htaccess', $this->get_filepath('.htaccess'));
		// // foreach(glob(POP_SERVICEWORKERS_ASSETS_DIR."/*") as $file) {
		// // 	copy($file, $this->get_dir());
		// // }
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_serviceworkers_manager;
$pop_serviceworkers_manager = new PoP_ServiceWorkers_Manager();