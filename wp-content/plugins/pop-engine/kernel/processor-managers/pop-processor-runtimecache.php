<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_ProcessorRuntimeCache {

	var $cache, $use_vars_identifier;
	function __construct() {

		$this->cache = array();

		// No need by default
		$this->use_vars_identifier = false;
	}

	function setUseVarsIdentifier($use_vars_identifier) {

		$this->use_vars_identifier = $use_vars_identifier;
	}

	function get_requestinstance_id() {

		// Comment Leo 18/09/2017: Since we need to pretend we are in other pages, to obtain their list of resources,
		// we need to incorporate all the $vars values into the cache
		if ($this->use_vars_identifier) {

			return PoP_VarsUtils::get_vars_identifier();
		}

		return 'general';
	}

	function delete_cache() {

		unset($this->cache[$this->get_requestinstance_id()]);
	}

	function is_cached($pagesection, $module, $template_id, $method) {

		// // Check that it is enabled. If not, always return false
		// if ($this->enabled) {

		if (!$pagesection) {

			// If there's no pagesection, it means the $atts argument arrived empty, that's an error
			throw new Exception(sprintf('Error Processing Request: pagesection empty (%s)', full_url()));
		}

		if (isset($this->cache[$this->get_requestinstance_id()][$pagesection][$module][$template_id][$method])) {

			return true;
		}
		// }

		return false;
	}

	function get_cache($pagesection, $module, $template_id, $method) {

		return $this->cache[$this->get_requestinstance_id()][$pagesection][$module][$template_id][$method];
	}

	function add_cache($pagesection, $module, $template_id, $method, $cache) {

		$this->cache[$this->get_requestinstance_id()][$pagesection][$module][$template_id][$method] = $cache;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processor_runtimecache;
$gd_template_processor_runtimecache = new GD_Template_ProcessorRuntimeCache();