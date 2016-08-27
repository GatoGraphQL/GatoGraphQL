<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_404', 'tabs-404');

class GD_DataLoad_TabIOHandler_404 extends GD_DataLoad_TabIOHandler {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_404;
	}

	protected function get_fontawesome() {
		return 'fa-exclamation-circle';
	}
	
	function get_title() {
		return __('Page not found!', 'poptheme-wassup');
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_404();