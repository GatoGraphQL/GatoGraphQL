<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_CRAWLABLEDATAPRINTER_USER', 'crawlabledataprinter-user');
 
class GD_DataLoad_CrawlableDataPrinter_User extends GD_DataLoad_CrawlableDataPrinter {

	function get_name() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_USER;
	}
	
	function get_crawlable_data($dataitem) {

		$output = parent::get_crawlable_data($dataitem);
		$elems = array();
		if ($dataitem['display-name']) {

			if ($dataitem['url']) {
				$elems[] = 
					sprintf(
						'<a href="%1$s" '.($dataitem['alt'] ? sprintf('alt="%s"', $dataitem['alt']) : '' ).'>%2$s</a>',
						$dataitem['url'], 
						$dataitem['display-name']
					);
			}
			else {
				$elems[] = $dataitem['display-name'];
			}
		}

		if ($dataitem['avatar']) {

			if ($dataitem['url']) {
				$elems[] = 
					sprintf(
						'<a href="%s" '.($dataitem['alt'] ? sprintf('alt="%s"', $dataitem['alt']) : '' ).'><img src="%s" width="%s" height="%s"</a>',
						$dataitem['url'], 
						$dataitem['avatar']['src'], 
						$dataitem['avatar']['width'], 
						$dataitem['avatar']['height']
					);
			}
			else {
				$elems[] = $dataitem['avatar'];
			}
		}

		if ($dataitem['userphoto']) {

			if ($dataitem['url']) {
				$elems[] = 
					sprintf(
						'<a href="%s" '.($dataitem['alt'] ? sprintf('alt="%s"', $dataitem['alt']) : '' ).'><img src="%s" width="%s" height="%s"</a>',
						$dataitem['url'], 
						$dataitem['userphoto']['src'], 
						$dataitem['userphoto']['width'], 
						$dataitem['userphoto']['height']
					);
			}
			else {
				$elems[] = $dataitem['userphoto'];
			}
		}

		if ($dataitem['description']) {
			$elems[] = $dataitem['description'];
		}
		if ($dataitem['description-formatted']) {
			$elems[] = $dataitem['description-formatted'];
		}

		$output .= implode('<br/>', $elems);
	
		return $output;
	}
}	
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_CrawlableDataPrinter_User();