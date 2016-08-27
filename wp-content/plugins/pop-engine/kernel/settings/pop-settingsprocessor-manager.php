<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_SettingsProcessor_Manager {

	var $processors, $sortedprocessors;

	function get_hierarchies() {

		global $gd_template_settingsmanager;
		return $gd_template_settingsmanager->get_hierarchies();
	}

	function get_processors() {

		// Needed for the Cache Generator
		return $this->processors;
	}
	
	function __construct() {

		$this->processors = array();
		$this->sortedprocessors = array();
		foreach ($this->get_hierarchies() as $hierarchy) {

			$this->sortedprocessors[$hierarchy] = array(
				'by-page' => array(),
				'by-block' => array(),
				'by-blockgroup' => array(),
				'by-action' => array()
			);
		}
	}
	
	function get_processor_by_page($page_id, $hierarchy) {
	
		return $this->sortedprocessors[$hierarchy]['by-page'][$page_id];
	}

	function get_processor_by_block($block, $hierarchy) {
	
		return $this->sortedprocessors[$hierarchy]['by-block'][$block];
	}

	function get_processor_by_blockgroup($blockgroup, $hierarchy) {
	
		return $this->sortedprocessors[$hierarchy]['by-blockgroup'][$blockgroup];
	}

	function get_processor_by_action($action, $hierarchy) {
	
		// Actions are hosted only for 
		return $this->sortedprocessors[$hierarchy]['by-action'][$action];
	}
	
	function add($processor) {

		$this->processors[] = $processor;
		foreach ($this->get_hierarchies() as $hierarchy) {
	
			foreach ($processor->get_page_blocks($hierarchy) as $page_id => $pagesettings) {
		
				$this->sortedprocessors[$hierarchy]['by-page'][$page_id] = $processor;
				if ($action = $pagesettings['action']) {
					$this->sortedprocessors[$hierarchy]['by-action'][$action] = $processor;
				}
				if ($blocks = $pagesettings['blocks']) {				
					foreach ($blocks as $format => $block) {
						$this->sortedprocessors[$hierarchy]['by-block'][$block] = $processor;
					}
				}
			}
			foreach ($processor->get_page_blockgroups($hierarchy) as $page_id => $pagesettings) {
		
				$this->sortedprocessors[$hierarchy]['by-page'][$page_id] = $processor;
				if ($blockgroups = $pagesettings['blockgroups']) {				
					foreach ($blockgroups as $format => $blockgroup) {
						$this->sortedprocessors[$hierarchy]['by-blockgroup'][$blockgroup] = $processor;
					}
				}
			}
			foreach ($processor->process_pages($hierarchy) as $page_id) {
		
				$this->sortedprocessors[$hierarchy]['by-page'][$page_id] = $processor;
			}
		}	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_settingsprocessor_manager;
$gd_template_settingsprocessor_manager = new GD_Template_SettingsProcessor_Manager();
