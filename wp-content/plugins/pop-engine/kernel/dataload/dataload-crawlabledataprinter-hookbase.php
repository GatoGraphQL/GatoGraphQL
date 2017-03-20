<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_CrawlableDataPrinter_HookBase {

	/**
	 * Function to override
	 */
	function get_crawlabledataprinters_to_hook() {

		return array();
	}

	function __construct() {

		foreach ($this->get_crawlabledataprinters_to_hook() as $dataprinter) {

			$filter = sprintf(GD_DATALOAD_CRAWLABLEDATAPRINTER_FILTER, $dataprinter);
			add_filter($filter, array($this, 'get_crawlable_data'), 10, 2);
		}
	}

	function get_crawlable_data($value, $dataitem) {

		return $value;
	}
}