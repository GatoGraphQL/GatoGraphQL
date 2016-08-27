<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_CrawlableDataPrinter {

	function __construct() {
    
		global $gd_dataloader_crawlabledataprinter_manager;
		$gd_dataloader_crawlabledataprinter_manager->add($this->get_name(), $this);
	}
	
	function get_name() {
	
		return '';
	}
	
	function get_crawlable_data($dataitem) {
	
		return '';
	}
}	
