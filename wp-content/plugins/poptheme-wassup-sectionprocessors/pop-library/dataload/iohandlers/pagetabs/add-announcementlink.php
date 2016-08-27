<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDANNOUNCEMENTLINK', 'tabs-page-addannouncementlink');

class GD_DataLoad_TabIOHandler_AddAnnouncementLinkPage extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDANNOUNCEMENTLINK;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENTLINK);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddAnnouncementLinkPage();