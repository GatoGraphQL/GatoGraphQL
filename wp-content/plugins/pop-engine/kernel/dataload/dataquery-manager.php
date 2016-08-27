<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataQuery_Manager {

    var $dataqueries;
    
    function __construct() {
    
		return $this->dataqueries = array();
	}
	
    function add($name, $dataquery) {
    
		$this->dataqueries[$name] = $dataquery;		
	}
	
	function get($name) {

		return $this->dataqueries[$name];
	}

	function get_allowedfields() {

		$allowedfields = array();
		foreach ($this->dataqueries as $name => $dataquery) {

			$allowedfields = array_merge(
				$allowedfields,
				$dataquery->get_allowedfields()
			);
		}

		return array_unique($allowedfields);
	}

	function get_allowedlayouts() {

		$allowedlayouts = array();
		foreach ($this->dataqueries as $name => $dataquery) {

			$allowedlayouts = array_merge(
				$allowedlayouts,
				$dataquery->get_allowedlayouts()
			);
		}

		return array_unique($allowedlayouts);
	}

	function get_cacheablepages() {

		$cacheablepages = array();
		foreach ($this->dataqueries as $name => $dataquery) {

			$cacheablepages[] = $dataquery->get_cacheable_page();
		}

		return $cacheablepages;
	}

	function get_noncacheablepages() {

		$noncacheablepages = array();
		foreach ($this->dataqueries as $name => $dataquery) {

			$noncacheablepages[] = $dataquery->get_noncacheable_page();
		}

		return $noncacheablepages;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_dataquery_manager;
$gd_dataquery_manager = new GD_DataQuery_Manager();