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

	// function get_precache_list($scope) {

	// 	$resources = array();
	// 	foreach ($this->jobs[$scope] as $job) {

	// 		$resources = array_merge(
	// 			$resources,
	// 			$job->get_precache_list()
	// 		);
	// 	}

	// 	// return array_unique($resources);
	// 	return $resources;
	// }

	// function get_excluded_paths($scope) {

	// 	$excluded = array(admin_url(), content_url(), includes_url());
	// 	foreach ($this->jobs[$scope] as $job) {

	// 		$excluded = array_merge(
	// 			$excluded,
	// 			$job->get_excluded_paths()
	// 		);
	// 	}

	// 	return array_unique($excluded);
	// }

  //   public function get_sw_configuration($scope) {
        
  //       $configuration = array(
  //           '$debug' => true,
  //           '$resources' => $this->get_precache_list($scope),
  //           '$excludedPaths' => $this->get_excluded_paths($scope),
  //       );

  //       foreach ($this->jobs[$scope] as $job) {

		// 	$configuration = array_merge(
		// 		$configuration,
		// 		$job->get_sw_configuration()
		// 	);
		// }
  //       return $configuration;
  //   }

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
    // private function render($path, $replacements) {

    //     $contents = file_get_contents($path);
    //     $incremental_hash = hash_init('md5');
    //     hash_update($incremental_hash, $contents);
    //     foreach ($replacements as $key => $replacement) {
    //         $value = json_encode($replacement);
    //         hash_update($incremental_hash, $value);
    //         $contents = str_replace($key, $value, $contents);
    //     }
    //     $version = json_encode(hash_final($incremental_hash));
    //     $contents = str_replace('$version', $version, $contents);
    //     return $contents;
    // }
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_serviceworkers_job_manager;
$pop_serviceworkers_job_manager = new PoP_ServiceWorkers_Job_Manager();