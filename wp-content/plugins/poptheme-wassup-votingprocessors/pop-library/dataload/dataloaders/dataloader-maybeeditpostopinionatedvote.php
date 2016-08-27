<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_MAYBEEDITPOSTOPINIONATEDVOTE', 'maybeeditpostopinionatedvote');
 
// We are assuming we are in either page or single templates
class GD_DataLoader_MaybeEditPostOpinionatedVoted extends GD_DataLoader_EditPost {

	function get_name() {
    
		return GD_DATALOADER_MAYBEEDITPOSTOPINIONATEDVOTE;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {
	
		// When editing a post in the frontend, set param "pid"
		// User must be logged in
		$pid = $_REQUEST['pid'];
		$query = array(
			// 'fields' => 'ids',
			'post_status' => array('publish', 'draft'),
			'author' => get_current_user_id(),
		);
		VotingProcessors_Template_Processor_CustomSectionBlocksUtils::add_dataloadqueryargs_opinionatedvotereferences($query, $pid);

		// OpinionatedVoteds are unique, just 1 per person/article. 
		// Check if there is a OpinionatedVoted for the given post.
		if ($opinionatedvotes = get_posts($query)) {

			return array($opinionatedvotes[0]);
		}
		return array();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_MaybeEditPostOpinionatedVoted();