<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_ADDON_ADDCOMMENT', 'tabs-addon-addcomment');

class GD_DataLoad_TabIOHandler_AddCommentAddon extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_ADDON_ADDCOMMENT;
	}

	function get_title() {

		return get_the_title(POP_WPAPI_PAGE_ADDCOMMENT);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddCommentAddon();