<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_ADDON_CONTACTUSER', 'tabs-addon-contactuser');

class GD_DataLoad_TabIOHandler_ContactProfileAddon extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_ADDON_CONTACTUSER;
	}

	function get_title() {

		return get_the_title(POP_GENERICFORMS_PAGE_CONTACTUSER);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_ContactProfileAddon();