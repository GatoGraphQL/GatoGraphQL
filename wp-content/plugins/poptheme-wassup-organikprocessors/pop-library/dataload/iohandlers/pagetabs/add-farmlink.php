<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDFARMLINK', 'tabs-page-addfarmlink');

class GD_DataLoad_TabIOHandler_AddFarmLinkPage extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDFARMLINK;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddFarmLinkPage();