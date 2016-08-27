<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDSTORY', 'tabs-page-addstory');

class GD_DataLoad_TabIOHandler_AddStoryPage extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDSTORY;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddStoryPage();