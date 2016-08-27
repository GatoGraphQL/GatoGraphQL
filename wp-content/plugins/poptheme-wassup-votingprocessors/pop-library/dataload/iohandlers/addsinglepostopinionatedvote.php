<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_ADDSINGLEPOSTOPINIONATEDVOTE', 'addsinglepostopinionatedvote');
define ('GD_DATALOAD_IOHANDLER_ADDPOSTOPINIONATEDVOTEORIGIN_SINGLEPOSTOPINIONATEDVOTE', 'singlepostopinionatedvote');

class GD_DataLoad_IOHandler_AddSinglePostOpinionatedVoted extends GD_DataLoad_BlockIOHandler {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_ADDSINGLEPOSTOPINIONATEDVOTE;
	}

	function get_params($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_params($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		global $post;
		$ret[GD_DATALOAD_PARAMS]['pid'] = $post->ID;

		// The origin will change the behaviour, what block/action to get in the settingsprocessor accordingly.
		$ret[GD_DATALOAD_PARAMS]['origin'] = GD_DATALOAD_IOHANDLER_ADDPOSTOPINIONATEDVOTEORIGIN_SINGLEPOSTOPINIONATEDVOTE;
		
		return $ret;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_AddSinglePostOpinionatedVoted();
