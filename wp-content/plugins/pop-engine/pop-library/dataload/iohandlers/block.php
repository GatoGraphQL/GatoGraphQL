<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_BLOCK', 'block');

class GD_DataLoad_IOHandler_Block extends GD_DataLoad_BlockIOHandler {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_BLOCK;
	}

	// function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
	// 	$ret = parent::get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);
	// 	$vars = $this->get_vars($vars_atts, $iohandler_atts);

	// 	// Hide Block?
	// 	$ret[GD_URLPARAM_HIDEBLOCK] = empty($dataset) && $iohandler_atts[GD_URLPARAM_HIDDENIFEMPTY];

	// 	return $ret;
	// }
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_Block();
