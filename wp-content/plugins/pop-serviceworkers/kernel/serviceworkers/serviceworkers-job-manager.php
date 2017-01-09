<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_ServiceWorkers_Job_Manager {

    var $jobs;
    
    function __construct() {
    
		return $this->jobs = array();
	}
	
    function add($scope, $job) {
    
		if (is_null($this->jobs[$scope])) {
			$this->jobs[$scope] = array();
		}
		$this->jobs[$scope][] = $job;		
	}
	
	function get($scope) {

		return $this->jobs[$scope];
	}

	public function get_dependencies($scope) {

		$dependencies = array();
		foreach ($this->jobs[$scope] as $job) {

			$dependencies = array_merge(
				$dependencies,
				$job->get_dependencies()
			);
		}

		return $dependencies;
	}

    public function render_sw($scope) {
        
        $parts = array();
        
		// Comment Leo 26/12/2016: No need to add the dependencies here, the actual files are instead copied to the dependencies folder
		// // Add the dependencies at the beginning (eg: localforage)
		// // They have a key so different jobs can override, or at least not duplicate, the same dependencies
		// foreach ($this->get_dependencies($scope) as $key => $dependency) {

		// 	$parts[] = file_get_contents($dependency);
		// }

        foreach ($this->jobs[$scope] as $job) {

			$parts[] = $this->render($job->get_sw_js_path(), $job->get_sw_configuration(), $job->get_sw_codereplacements());
		}
		return implode(/*';'*/PHP_EOL, $parts);
    }

    private function render($path, $replacements, $codereplacements) {

        $contents = file_get_contents($path);
        foreach ($replacements as $key => $replacement) {
            $value = json_encode($replacement);
            $contents = str_replace($key, $value, $contents);
        }
        foreach ($codereplacements as $key => $replacement) {
            $value = $replacement;
            $contents = str_replace($key, $value, $contents);
        }
        return $contents;
    }
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_serviceworkers_job_manager;
$pop_serviceworkers_job_manager = new PoP_ServiceWorkers_Job_Manager();