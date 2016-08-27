<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoader_FieldProcessor_Manager {

    var $configuration;
    
    function __construct() {
    
		return $this->configuration = array();
	}
	
    function add($dataloader, $fieldprocessor) {
    
		$this->configuration[$dataloader] = $fieldprocessor;
	}
	
	function get_fieldprocessor($dataloader) {

		return $this->configuration[$dataloader];
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_dataloader_fieldprocessor_manager;
$gd_dataloader_fieldprocessor_manager = new GD_DataLoader_FieldProcessor_Manager();