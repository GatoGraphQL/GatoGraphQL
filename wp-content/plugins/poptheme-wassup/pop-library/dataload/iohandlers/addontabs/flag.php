<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_ADDON_FLAG', 'tabs-addon-flag');

class GD_DataLoad_TabIOHandler_FlagAddon extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_ADDON_FLAG;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_GF_PAGE_FLAG);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_FlagAddon();