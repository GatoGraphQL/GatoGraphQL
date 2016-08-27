<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TRENDINGTAGLIST', 'trendingtag-list');

class GD_DataLoad_IOHandler_TrendingTagList extends GD_DataLoad_IOHandler_List {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_TRENDINGTAGLIST;
	}

	function get_vars($atts, $iohandler_atts) {
    
		$ret = parent::get_vars($atts, $iohandler_atts);

		$days = $atts['days'];

		// One Week by default		
		$ret['days'] = $days ? intval($days) : POP_WPAPI_DAYS_TRENDINGTAGS;

		return $ret;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_TrendingTagList();
