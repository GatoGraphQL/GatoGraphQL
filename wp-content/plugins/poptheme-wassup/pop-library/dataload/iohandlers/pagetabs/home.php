<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_HOME', 'tabs-home');

class GD_DataLoad_TabIOHandler_Home extends GD_DataLoad_TabIOHandler {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_HOME;
	}

	protected function get_fontawesome() {
		return 'fa-home';
	}

	function get_title() {
		return __('Home', 'poptheme-wassup');
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_Home();