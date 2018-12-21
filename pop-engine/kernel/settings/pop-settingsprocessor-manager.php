<?php
namespace PoP\Engine\Settings;

class SettingsProcessor_Manager {

	var $processors, $default_processor;

	function get_processors() {

		// Needed for the Cache Generator
		return $this->processors;
	}

	function get_pages() {

		// Filter out $page with no value, since the ID might've not been defined for that page
		return array_filter(array_keys($this->processors));
	}
	
	function __construct() {

		SettingsProcessorManager_Factory::set_instance($this);		
		$this->processors = array();
	}
	
	function get_processor($page_id) {

		if ($this->processors[$page_id]) {

			return $this->processors[$page_id];
		}

		if ($this->default_processor) {

			return $this->default_processor;
		}

		throw new \Exception(sprintf('No Settings Processor for $page_id \'%s\' (%s)', $page_id, full_url()));
	}
	
	function add($processor) {

		foreach ($processor->pages_to_process() as $page_id) {
	
			$this->processors[$page_id] = $processor;
		}
	}
	
	function set_default($processor) {

		$this->default_processor = $processor;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new SettingsProcessor_Manager();
