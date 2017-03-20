<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_Custom_DataLoad_CrawlableDataPrinter_Users_Hook extends GD_DataLoad_CrawlableDataPrinter_HookBase {

	function get_crawlabledataprinters_to_hook() {

		return array(GD_DATALOAD_CRAWLABLEDATAPRINTER_USER);
	}

	function get_crawlable_data($output, $dataitem) {

		$elems = array();
		$placeholder = __('<a href="%1$s">%2$s: %1$s</a>', 'poptheme-wassup');

		if ($dataitem['facebook']) {

			$elems[] = sprintf(
				$placeholder,
				$dataitem['facebook'],
				__('Facebook', 'poptheme-wassup')
			);
		}
		if ($dataitem['twitter']) {

			$elems[] = sprintf(
				$placeholder,
				$dataitem['twitter'],
				__('Twitter', 'poptheme-wassup')
			);
		}
		if ($dataitem['linkedin']) {

			$elems[] = sprintf(
				$placeholder,
				$dataitem['linkedin'],
				__('LinkedIn', 'poptheme-wassup')
			);
		}
		if ($dataitem['youtube']) {

			$elems[] = sprintf(
				$placeholder,
				$dataitem['youtube'],
				__('Youtube', 'poptheme-wassup')
			);
		}
		if ($dataitem['instagram']) {

			$elems[] = sprintf(
				$placeholder,
				$dataitem['instagram'],
				__('Instagram', 'poptheme-wassup')
			);
		}
		if ($dataitem['blog']) {

			$elems[] = sprintf(
				$placeholder,
				$dataitem['blog'],
				__('Blog', 'poptheme-wassup')
			);
		}

		$output .= implode('<br/>', $elems);
	
		return $output;
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Custom_DataLoad_CrawlableDataPrinter_Users_Hook();