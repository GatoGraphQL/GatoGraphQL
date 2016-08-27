<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDDISCUSSION', 'tabs-page-adddiscussion');

class GD_DataLoad_TabIOHandler_AddDiscussionPage extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDDISCUSSION;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddDiscussionPage();