<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_Custom_DataLoad_CrawlableDataPrinter_Users_Hook extends GD_DataLoad_CrawlableDataPrinter_HookBase {

	function get_crawlabledataprinters_to_hook() {

		return array(GD_DATALOAD_CRAWLABLEDATAPRINTER_USER);
	}

	function get_crawlable_data($output, $dataitem) {

		$elems = array();

		if ($dataitem['short-description']) {

			$elems[] = $dataitem['short-description'];
		}
		if ($dataitem['short-description-formatted']) {

			$elems[] = $dataitem['short-description-formatted'];
		}

		$output .= implode('<br/>', $elems);
	
		return $output;
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_DataLoad_CrawlableDataPrinter_Users_Hook();