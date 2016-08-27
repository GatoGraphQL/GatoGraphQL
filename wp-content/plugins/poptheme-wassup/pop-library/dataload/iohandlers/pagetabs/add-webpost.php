<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDWEBPOST', 'tabs-page-addwebpost');

class GD_DataLoad_TabIOHandler_AddWebPostPage extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDWEBPOST;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_PAGE_ADDWEBPOST);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddWebPostPage();