<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDFARM', 'tabs-page-addfarm');

class GD_DataLoad_TabIOHandler_AddFarmPage extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDFARM;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddFarmPage();