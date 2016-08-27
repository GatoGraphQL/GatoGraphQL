<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE', 'tabs-page');

class GD_DataLoad_TabIOHandler_Page extends GD_DataLoad_TabIOHandler {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE;
	}

	// protected function get_fontawesome() {
		
	// 	global $post;
	// 	return gd_navigation_menu_item($post->ID);
	// }
	
	function get_title() {

		// global $post;
		// return get_the_title($post->ID);
		return get_the_title();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_Page();