<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_TAG', 'tabs-tag');

class GD_DataLoad_TabIOHandler_Tag extends GD_DataLoad_TabIOHandler {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_TAG;
	}

	protected function get_fontawesome() {
		return 'fa-hashtag';
	}

	function get_title() {
		return single_tag_title("", false);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_Tag();