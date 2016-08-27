<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_CRAWLABLEDATAPRINTER_LOCATION', 'crawlabledataprinter-location');

class GD_DataLoad_CrawlableDataPrinter_Location extends GD_DataLoad_CrawlableDataPrinter_Post {

	function get_name() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_LOCATION;
	}
	
	function get_crawlable_data($dataitem) {

		$output = parent::get_crawlable_data($dataitem);
		$elems = array();

		if ($dataitem['name']) {
			$elems[] = $dataitem['name'];
		}
		if ($dataitem['address']) {
			$elems[] = $dataitem['address'];
		}

		$output .= implode('<br/>', $elems);
	
		return $output;
	}
}	

	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_CrawlableDataPrinter_Location();