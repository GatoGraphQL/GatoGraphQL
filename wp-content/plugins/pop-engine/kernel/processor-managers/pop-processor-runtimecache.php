<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_ProcessorRuntimeCache {

	var $cache;
	function __construct() {

		$this->cache = array();
	}

	// protected function get_pagesection($pagesection) {

	// 	if (!$pagesection) {

	// 		return 'general';
	// 	}

	// 	return $pagesection;
	// }

	function is_cached($pagesection, $module, $template_id, $method) {

		if (!$pagesection) {

			// If there's no pagesection, it means the $atts argument arrived empty, that's an error
			throw new Exception("Error Processing Request: pagesection empty", 1);
		}

		// $pagesection = $this->get_pagesection($pagesection);
		if (isset($this->cache[$pagesection][$module][$template_id][$method])) {

			return true;
		}

		return false;
	}

	function get_cache($pagesection, $module, $template_id, $method) {

		// $pagesection = $this->get_pagesection($pagesection);
		return $this->cache[$pagesection][$module][$template_id][$method];
	}

	function add_cache($pagesection, $module, $template_id, $method, $cache) {

		// $pagesection = $this->get_pagesection($pagesection);
		$this->cache[$pagesection][$module][$template_id][$method] = $cache;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processor_runtimecache;
$gd_template_processor_runtimecache = new GD_Template_ProcessorRuntimeCache();