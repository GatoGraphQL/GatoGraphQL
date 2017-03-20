<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_URE_Custom_DataLoad_CrawlableDataPrinter_Users_Hook extends GD_DataLoad_CrawlableDataPrinter_HookBase {

	function get_crawlabledataprinters_to_hook() {

		return array(GD_DATALOAD_CRAWLABLEDATAPRINTER_USER);
	}

	function get_crawlable_data($output, $dataitem) {

		$elems = array();

		$placeholder = __('<p>%s: %s</p>', 'poptheme-wassup');
		$comma = __(', ', 'poptheme-wassup');
		if ($dataitem['contact-person']) {

			$elems[] = sprintf(
				$placeholder,
				__('Contact Person', 'poptheme-wassup'),
				$dataitem['contact-person']
			);
		}
		if ($dataitem['organizationtypes-strings']) {
			
			$elems[] = sprintf(
				$placeholder,
				__('Type', 'poptheme-wassup'),
				implode($comma, $dataitem['organizationtypes-strings'])
			);
		}
		if ($dataitem['organizationcategories-strings']) {
			
			$elems[] = sprintf(
				$placeholder,
				__('Categories', 'poptheme-wassup'),
				implode($comma, $dataitem['organizationcategories-strings'])
			);
		}
		if ($dataitem['individualinterests-strings']) {
			
			$elems[] = sprintf(
				$placeholder,
				__('Interests', 'poptheme-wassup'),
				implode($comma, $dataitem['individualinterests-strings'])
			);
		}

		$output .= implode('<br/>', $elems);
	
		return $output;
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_DataLoad_CrawlableDataPrinter_Users_Hook();