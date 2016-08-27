<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDWEBPOSTLINK', 'tabs-page-addwebpostlink');

class GD_DataLoad_TabIOHandler_AddLinkPage extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDWEBPOSTLINK;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddLinkPage();