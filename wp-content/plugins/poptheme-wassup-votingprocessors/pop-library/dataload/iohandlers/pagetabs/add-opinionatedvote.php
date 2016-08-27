<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDOPINIONATEDVOTE', 'tabs-page-addopinionatedvote');

class GD_DataLoad_TabIOHandler_AddOpinionatedVotedPage extends GD_DataLoad_TabIOHandler_Page {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDOPINIONATEDVOTE;
	}

	function get_title() {

		return get_the_title(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_ADDOPINIONATEDVOTE);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_AddOpinionatedVotedPage();