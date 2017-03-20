<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_DATALOAD_CRAWLABLEDATAPRINTER_FILTER', 'gd_template:dataload_crawlabledataprinter:%s');

class GD_DataLoad_CrawlableDataPrinter {

	function __construct() {
    
		global $gd_dataloader_crawlabledataprinter_manager;
		$gd_dataloader_crawlabledataprinter_manager->add($this->get_name(), $this);
	}
	
	function get_name() {
	
		return '';
	}
	
	function get_crawlable_data($dataitem) {

		// Let the hooks add their own data
		return apply_filters(
			sprintf(
				GD_DATALOAD_CRAWLABLEDATAPRINTER_FILTER, 
				$this->get_name()
			),
			'',
			$dataitem
		);
	}
}	
