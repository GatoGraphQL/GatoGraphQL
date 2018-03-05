<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_ADDON_VOLUNTEER', 'tabs-addon-volunteer');

class GD_DataLoad_TabIOHandler_VolunteerAddon extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_ADDON_VOLUNTEER;
	}

	function get_title() {

		return get_the_title(POP_GENERICFORMS_PAGE_VOLUNTEER);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_VolunteerAddon();