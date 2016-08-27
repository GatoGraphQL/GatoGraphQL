<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_CRAWLABLEDATAPRINTER_TAG', 'crawlabledataprinter-tag');
 
class GD_DataLoad_CrawlableDataPrinter_Tag extends GD_DataLoad_CrawlableDataPrinter {

	function get_name() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_TAG;
	}
	
	function get_crawlable_data($dataitem) {

		$output = parent::get_crawlable_data($dataitem);
		$elems = array();
		if ($dataitem['name']) {

			if ($dataitem['url']) {
				$elems[] = 
					sprintf(
						'<a href="%1$s">%2$s</a>',
						$dataitem['url'], 
						$dataitem['name']
					);
			}
			else {
				$elems[] = $dataitem['name'];
			}
		}

		$output .= implode('<br/>', $elems);
	
		return $output;
	}
}	
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_CrawlableDataPrinter_Tag();