<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_CRAWLABLEDATAPRINTER_COMMENT', 'crawlabledataprinter-comment');

class GD_DataLoad_CrawlableDataPrinter_Comment extends GD_DataLoad_CrawlableDataPrinter {

	function get_name() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_COMMENT;
	}
	
	function get_crawlable_data($dataitem) {

		$output = parent::get_crawlable_data($dataitem);
		$elems = array();
		if ($dataitem['content']) {

			// Using htmlentities so that it doesn't include some html. Eg: embedding iframes for the Link category
			// $elems[] = htmlentities($dataitem['content']);
			$elems[] = PoP_CrawlableDataPrinter_Utils::strip_content_tags($dataitem['content']);
		}

		if ($dataitem['author']) {

			if ($dataitem['author-url']) {
				$elems[] = 
					sprintf(
						'<a href="%1$s">%2$s</a>',
						$dataitem['author-url'], 
						$dataitem['author']
					);
			}
			else {
				$elems[] = $dataitem['author'];
			}
		}

		$output .= implode('<br/>', $elems);
	
		return $output;
	}
}	

	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_CrawlableDataPrinter_Comment();

