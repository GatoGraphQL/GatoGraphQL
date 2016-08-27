<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDSTORYLINK', 'tabs-page-addstorylink');

class GD_DataLoad_TabIOHandler_AddStoryLinkPage extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDSTORYLINK;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORYLINK);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddStoryLinkPage();