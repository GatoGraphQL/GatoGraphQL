<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_CDNCore_Job_Manager {

    var $jobs;
    
    function __construct() {
    
		$this->jobs = array();
	}
	
    function add($job) {
    
		$this->jobs[] = $job;		
	}
	
	function get() {

		return $this->jobs;
	}

    public function render() {
        
        $parts = array();
        
		foreach ($this->jobs as $job) {

			$parts[] = $this->render_job($job->get_js_path(), $job->get_configuration());
		}
		return implode(/*';'*/PHP_EOL, $parts);
    }

    private function render_job($path, $replacements) {

        $contents = file_get_contents($path);
        foreach ($replacements as $key => $replacement) {
            $value = json_encode($replacement);
            $contents = str_replace($key, $value, $contents);
        }
        return $contents;
    }
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_cdncore_job_manager;
$pop_cdncore_job_manager = new PoP_CDNCore_Job_Manager();