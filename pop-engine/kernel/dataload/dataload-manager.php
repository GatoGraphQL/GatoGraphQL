<?php
namespace PoP\Engine;
 
class Dataloader_Manager {

    var $dataloaders;
    
    function __construct() {
    
    	Dataloader_Manager_Factory::set_instance($this);    
		return $this->dataloaders = array();
	}
	
    function add($dataloader) {
    
		$this->dataloaders[$dataloader->get_name()] = $dataloader;
	}
	
	function get($name) {

		$dataloader = $this->dataloaders[$name];
		if (!$dataloader) {

			throw new \Exception(sprintf('There is no Dataloader with name \'%s\' (%s)', $name, full_url()));
		}

		return $dataloader;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new Dataloader_Manager();