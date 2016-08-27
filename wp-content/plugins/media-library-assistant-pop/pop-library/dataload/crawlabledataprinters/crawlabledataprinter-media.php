<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_CRAWLABLEDATAPRINTER_MEDIA', 'crawlabledataprinter-media');

class GD_DataLoad_CrawlableDataPrinter_Media extends GD_DataLoad_CrawlableDataPrinter_Post {

	function get_name() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_MEDIA;
	}
	
	function get_crawlable_data($dataitem) {

		$output = parent::get_crawlable_data($dataitem);
		$elems = array();

		if ($dataitem['filename']) {

			if ($dataitem['url']) {
				$elems[] = 
					sprintf(
						'<a href="%1$s" '.($dataitem['alt'] ? sprintf('alt="%s"', $dataitem['alt']) : '' ).'>%2$s</a>',
						$dataitem['url'], $dataitem['filename']
					);
			}
			else {
				$elems[] = $dataitem['filename'];
			}
		}

		if ($dataitem['description']) {
			$elems[] = $dataitem['description'];
		}
		if ($dataitem['caption']) {
			$elems[] = $dataitem['caption'];
		}
		if ($dataitem['name']) {
			$elems[] = $dataitem['name'];
		}
		if ($dataitem['source']) {
			$elems[] = $dataitem['source'];
		}

		$output .= implode('<br/>', $elems);
	
		return $output;
	}
}	

	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_CrawlableDataPrinter_Media();

