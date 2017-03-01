<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_CALENDAR', 'calendar');

// class GD_DataLoad_IOHandler_Calendar extends GD_DataLoad_IOHandler_ListNoLimit_Event {
class GD_DataLoad_IOHandler_Calendar extends GD_DataLoad_IOHandler_List {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_CALENDAR;
	}

    function get_vars($atts, $iohandler_atts) {
    
		$ret = parent::get_vars($atts, $iohandler_atts);
		$today = POP_CONSTANT_TIME;

		$year = $atts[GD_URLPARAM_YEAR] ? $atts[GD_URLPARAM_YEAR] : date('Y', $today);
		// Format 'n': do not include leading zeros
		$month = $atts[GD_URLPARAM_MONTH] ? $atts[GD_URLPARAM_MONTH] : date('n', $today);

		// For EM to filter, the format must be exactly 'Y-m-d', otherwise it doesn't work!!!
		$from = date('Y-m-01', strtotime($year.'-'.$month.'-01'));
		$to = date('Y-m-t', strtotime($from)); // 't' gives the amount of days in the month

		$ret['scope'] = array($from, $to);

		// Also give back the day and year, for the JS to execute fullCalendar.gotoDate
		$ret[GD_URLPARAM_YEAR] = $year;
		$ret[GD_URLPARAM_MONTH] = $month;

		// // If the Limit is not set, then bring all results
		// // This is needed so that for Events Calendar, we can still use GD_DATALOAD_IOHANDLER_CALENDAR
		// // to get Events for the FullView scroll on offcanvas-main, which must be paged
		// if (!isset($atts[GD_URLPARAM_LIMIT])) {
		// 	$ret[GD_URLPARAM_LIMIT] = 0;
		// }

		// Always bring all results
		$ret[GD_URLPARAM_LIMIT] = 0;

		return $ret;
	}
	
	
	function get_params($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_params($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		$vars = $this->get_vars($vars_atts, $iohandler_atts);
		
		// Send back the year / month
		$ret[GD_DATALOAD_VISIBLEPARAMS][GD_URLPARAM_YEAR] = $vars[GD_URLPARAM_YEAR];
		$ret[GD_DATALOAD_VISIBLEPARAMS][GD_URLPARAM_MONTH] = $vars[GD_URLPARAM_MONTH];

		// Never stop fetching! (Or otherwise it doesn't allow to go prev/next with Calendar buttons)
		$ret[GD_URLPARAM_STOPFETCHING] = false;

		return $ret;
	}
	

}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_Calendar();
