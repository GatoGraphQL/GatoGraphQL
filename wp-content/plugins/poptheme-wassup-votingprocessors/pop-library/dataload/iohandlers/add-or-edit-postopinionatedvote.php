<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_ADDOREDITPOSTOPINIONATEDVOTE', 'add-or-edit-postopinionatedvote');

class GD_DataLoad_IOHandler_AddOrEditPostOpinionatedVoted extends GD_DataLoad_IOHandler_Form {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_ADDOREDITPOSTOPINIONATEDVOTE;
	}

	function get_params($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_params($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		// Initially, return the same "pid" as provided. This is so that, if the user is not logged in, the "pid" is not overwritten with nothing
		// (The "origin" param, with value "singlepostopinionatedvote" is already set in dataload-iohandler.php)
		$pid = $_REQUEST['pid'];
		$ret[GD_DATALOAD_PARAMS]['pid'] = $pid;

		// page /add-or-edit-postopinionatedvote will be called always, either with user logged in or not.
		// Only if user is logged in, try to get his/her opinionatedvote for this post
		if (is_user_logged_in()) {
		
			// blank means Create new opinionatedvote. It's the default for the logged in user, meaning create a new opinionatedvote. Will be overwritten below if user has already a OpinionatedVoted
			$ret[GD_DATALOAD_PARAMS]['pid'] = '';
			$ret[GD_DATALOAD_PARAMS]['_wpnonce'] = '';
			
			$query = array(
				// 'fields' => 'ids',
				'author' => get_current_user_id(),
				'post_status' => array('publish', 'draft'),
			);
			VotingProcessors_Template_Processor_CustomSectionBlocksUtils::add_dataloadqueryargs_opinionatedvotereferences($query, $pid);

			// OpinionatedVoteds are unique, just 1 per person/article. 
			// Check if there is a OpinionatedVoted for the given post.
			if ($opinionatedvotes = get_posts($query)) {

				$ret[GD_DATALOAD_PARAMS]['pid'] = $opinionatedvotes[0];
				$ret[GD_DATALOAD_PARAMS]['_wpnonce'] = gd_create_nonce(GD_NONCE_EDITURL, $opinionatedvotes[0]);
			}
		}
		
		return $ret;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_AddOrEditPostOpinionatedVoted();
