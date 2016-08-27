<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDEVENTLINK', 'tabs-page-addeventlink');

class GD_DataLoad_TabIOHandler_AddEventLinkPage extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDEVENTLINK;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddEventLinkPage();