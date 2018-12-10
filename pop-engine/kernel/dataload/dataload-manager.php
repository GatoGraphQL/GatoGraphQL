<?php
 
class GD_DataLoad_Manager {

    var $dataloaders;
    
    function __construct() {
    
		return $this->dataloaders = array();
	}
	
    function add($dataloader) {
    
		$this->dataloaders[$dataloader->get_name()] = $dataloader;
	}
	
	function get($name) {

		$dataloader = $this->dataloaders[$name];
		if (!$dataloader) {

			throw new Exception(sprintf('Ther is no Dataloader with name \'%s\' (%s)', $name, full_url()));
		}

		return $dataloader;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_dataload_manager;
$gd_dataload_manager = new GD_DataLoad_Manager();