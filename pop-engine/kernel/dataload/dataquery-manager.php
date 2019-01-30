<?php
namespace PoP\Engine;
 
class DataQuery_Manager {

    var $dataqueries;
    
    function __construct() {
    
    	DataQuery_Manager_Factory::set_instance($this);    
		return $this->dataqueries = array();
	}
	
    function add($name, $dataquery) {
    
		$this->dataqueries[$name] = $dataquery;		
	}
	
	function get($name) {

		return $this->dataqueries[$name];
	}

	function filter_allowedfields($fields) {

		// Choose if to reject fields, starting from all of them...
		// By default, if API is enabled, then use this method
		if (apply_filters('DataQuery_Manager:filter_by_rejection', Server\Utils::enable_api())) {

			return array_values(array_diff(
				$fields, 
				$this->get_rejectedfields()
			));
		}

		// ... or choose to allow fields, starting from an empty list
		// Used to restrict access to the site, and only offer those fields needed for lazy loading
		return array_values(array_intersect(
			$fields, 
			$this->get_allowedfields()
		));
	}

	function filter_allowedlayouts($layouts) {

		// // If allow to return all layouts, then no need to filter them
		// if (apply_filters('DataQuery_Manager:allow_all_layouts', false)) {

		// 	return $layouts;
		// }

		return array_values(array_intersect(
			$layouts, 
			$this->get_allowedlayouts()
		));
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

	function get_rejectedfields() {

		$rejectedfields = array();
		foreach ($this->dataqueries as $name => $dataquery) {

			$rejectedfields = array_merge(
				$rejectedfields,
				$dataquery->get_rejectedfields()
			);
		}

		return array_unique($rejectedfields);
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
new DataQuery_Manager();