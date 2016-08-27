<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_CRAWLABLEDATAPRINTER_POST', 'crawlabledataprinter-post');

class GD_DataLoad_CrawlableDataPrinter_Post extends GD_DataLoad_CrawlableDataPrinter {

	function get_name() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_POST;
	}
	
	function get_crawlable_data($dataitem) {

		$output = parent::get_crawlable_data($dataitem);
		$elems = array();
		if ($dataitem['title']) {

			if ($dataitem['url']) {
				// $output = 
				// 	sprintf(
				// 		'<a href="%1$s" title="%2$s" '.($dataitem['alt'] ? sprintf('alt="%s"', $dataitem['alt']) : '' ).'>%2$s</a>',
				// 		$dataitem['url'], $dataitem['title']
				// 	);
				$elems[] = 
					sprintf(
						'<a href="%1$s" '.($dataitem['alt'] ? sprintf('alt="%s"', $dataitem['alt']) : '' ).'>%2$s</a>',
						$dataitem['url'], 
						$dataitem['title']
					);
			}
			else {
				$elems[] = $dataitem['title'];
			}
		}

		if ($dataitem['featuredimage-imgsrc']) {

			if ($dataitem['url']) {
				$elems[] = 
					sprintf(
						'<a href="%s" '.($dataitem['alt'] ? sprintf('alt="%s"', $dataitem['alt']) : '' ).'><img src="%s" width="%s" height="%s"</a>',
						$dataitem['url'], 
						$dataitem['featuredimage-imgsrc']['src'], 
						$dataitem['featuredimage-imgsrc']['width'], 
						$dataitem['featuredimage-imgsrc']['height']
					);
			}
			else {
				$elems[] = $dataitem['featuredimage-imgsrc'];
			}
		}

		if ($dataitem['content']) {
			// Using htmlentities so that it doesn't include some html. Eg: embedding iframes for the Link category
			// $elems[] = htmlentities($dataitem['content']);
			$elems[] = PoP_CrawlableDataPrinter_Utils::strip_content_tags($dataitem['content']);
		}
		// if ($dataitem['content-plain']) {
		// 	$elems[] = $dataitem['content-plain'];
		// }
		if ($dataitem['excerpt']) {
			$elems[] = $dataitem['excerpt'];
		}

		$output .= implode('<br/>', $elems);
	
		return $output;
	}
}	

	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_CrawlableDataPrinter_Post();

