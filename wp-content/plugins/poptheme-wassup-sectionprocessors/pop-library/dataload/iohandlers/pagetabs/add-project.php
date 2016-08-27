<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDPROJECT', 'tabs-page-addproject');

class GD_DataLoad_TabIOHandler_AddProjectPage extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDPROJECT;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDPROJECT);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddProjectPage();