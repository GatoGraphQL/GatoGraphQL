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

			$avatar = sprintf(
				'<img src="%s" width="%s" height="%s">',
				$dataitem['avatar']['src'], 
				$dataitem['avatar']['width'], 
				$dataitem['avatar']['height']
			);
			if ($dataitem['url']) {
				$elems[] = sprintf(
					'<a href="%s" '.($dataitem['alt'] ? sprintf('alt="%s"', $dataitem['alt']) : '' ).'>%s</a>',
					$dataitem['url'], 
					$avatar
				);
			}
			else {
				$elems[] = $dataitem['avatar'];
			}
		}

		if ($dataitem['userphoto']) {

			$userphoto = sprintf(
				'<img src="%s" width="%s" height="%s">',
				$dataitem['userphoto']['src'], 
				$dataitem['userphoto']['width'], 
				$dataitem['userphoto']['height']
			);
			if ($dataitem['url']) {
				$elems[] = sprintf(
					'<a href="%s" '.($dataitem['alt'] ? sprintf('alt="%s"', $dataitem['alt']) : '' ).'>%s</a>',
					$dataitem['url'], 
					$userphoto
				);
			}
			else {
				$elems[] = $userphoto;
			}
		}

		if ($dataitem['description']) {
			$elems[] = $dataitem['description'];
		}
		if ($dataitem['description-formatted']) {
			$elems[] = $dataitem['description-formatted'];
		}
		if ($dataitem['username']) {
			$elems[] = sprintf(
				__('<p>%s: %s</p>', 'poptheme-wassup'),
				__('Username', 'poptheme-wassup'),
				$dataitem['username']
			);
		}
		if ($dataitem['excerpt']) {
			$elems[] = $dataitem['excerpt'];
		}

		if ($dataitem['user-url']) {

			$elems[] = sprintf(
				'<a href="%1$s">%2$s: %1$s</a>',
				$dataitem['user-url'],
				__('Website', 'poptheme-wassup')
			);
		}

		// Contact: any of the 2 data-fields will do
		$contact = $dataitem['contact'] ?? $dataitem['contact-small'];
		if ($contact) {

			foreach ($contact as $contactitem) {
				$elems[] = sprintf(
					__('<p><a href="%1$s" alt="%2$s">%3$s: %1$s</a></p>', 'pop-wpapi'),
					$contactitem['url'],
					$contactitem['tooltip'] ?? '',
					$contactitem['text'] ?? $contactitem['tooltip'] ?? ''
				);
			}
		}

		$output .= implode('<br/>', $elems);
	
		return $output;
	}
}	
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_CrawlableDataPrinter_User();