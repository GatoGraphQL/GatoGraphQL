<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_CALENDAR_NOLIMIT', 'calendar-nolimit');

// class GD_DataLoad_IOHandler_Calendar extends GD_DataLoad_IOHandler_ListNoLimit_Event {
class GD_DataLoad_IOHandler_NoLimitCalendar extends GD_DataLoad_IOHandler_Calendar {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_CALENDAR_NOLIMIT;
	}
	
	
	function get_params($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_params($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		// Never stop fetching! (Or otherwise it doesn't allow to go prev/next with Calendar buttons)
		// $ret[GD_DATALOAD_INTERNALPARAMS][GD_URLPARAM_STOPFETCHING] = false;
		$ret[GD_URLPARAM_STOPFETCHING] = false;

		return $ret;
	}
	

}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_NoLimitCalendar();
