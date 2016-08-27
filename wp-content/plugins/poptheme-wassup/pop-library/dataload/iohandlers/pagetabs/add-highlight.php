<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDHIGHLIGHT', 'tabs-page-addhighlight');

class GD_DataLoad_TabIOHandler_AddHighlightPage extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDHIGHLIGHT;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddHighlightPage();