<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_CRAWLABLEDATAPRINTER_MENU', 'crawlabledataprinter-menu');
 
class GD_DataLoad_CrawlableDataPrinter_Menu extends GD_DataLoad_CrawlableDataPrinter {

	function get_name() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_MENU;
	}
	
	function get_crawlable_data($dataitem) {

		$output = parent::get_crawlable_data($dataitem);
		$elems = array();
		if ($items = wp_get_nav_menu_items($dataitem['id'])) {
			foreach ($items as $menu_item) {

				// If it is the divider, then skip
				if ($menu_item->url == '#') continue;

				$title = apply_filters('the_title', $menu_item->title, $menu_item->object_id);
				$elems[] = 
					sprintf(
						'<a href="%1$s" alt="%2$s">%3$s</a>',
						$menu_item->url, 
						$menu_item->title,
						$title
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
new GD_DataLoad_CrawlableDataPrinter_Menu();