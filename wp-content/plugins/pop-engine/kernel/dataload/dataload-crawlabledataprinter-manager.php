<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_CrawlableDataPrinter_Manager {

    var $printers;
    
    function __construct() {
    
		return $this->printers = array();
	}
	
    function add($name, $printer) {
    
		$this->printers[$name] = $printer;
	}
	
	function get_printer($name) {

		return $this->printers[$name];
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_dataloader_crawlabledataprinter_manager;
$gd_dataloader_crawlabledataprinter_manager = new GD_DataLoad_CrawlableDataPrinter_Manager();