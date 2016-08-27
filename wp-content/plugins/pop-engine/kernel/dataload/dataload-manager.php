<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_Manager {

    var $dataloaders;
    
    function __construct() {
    
		return $this->dataloaders = array();
	}
	
    function add($name, $execution_priority, $dataloader) {
    
		$this->dataloaders[$name] = array(
			'priority' => $execution_priority,
			'dataloader' => $dataloader
		);
		
		
	}
	
	function get($name) {

		return $this->dataloaders[$name]['dataloader'];
	}
	
	// Priority goes from 1 to 5 (already predefined)
	protected function get_priority($name) {
    
		return $this->dataloaders[$name]['priority'];
	}
	
	/**
	 * Returns a list of lists of dataloaders, ordered by their priority: then 2 different dataloaders can have the same priority
	 */
	function order($dataloaders) {

		// Provide the list of dataloaders ordered by their execution priority
		// Priority goes from 1 to 5 (already predefined)
		$ret = new SplFixedArray(5);
		foreach ($dataloaders as $dataloader_name) {
		
			$priority = $this->get_priority($dataloader_name);
			$pos = $priority - 1;
			$list = $ret[$pos];

			// Priority goes from 1 onwards
			if (is_null($list)) {
			
				// Each slot contains a list of dataloaders with that given priority
				$list = array();
			} 
			
			// add the dataloader to the list
			$list[] = $dataloader_name;
			$ret[$pos] = $list;
		}
		
		$ret = $ret->toArray();
		
		// Remove all empty slots and return in 1 dimension
		return array_flatten(array_filter($ret));
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_dataload_manager;
$gd_dataload_manager = new GD_DataLoad_Manager();