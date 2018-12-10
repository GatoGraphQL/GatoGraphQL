<?php
 
define ('GD_DATALOADER_COMMENTLIST', 'comments-list');

class GD_Dataloader_CommentList extends GD_Dataloader_CommentListBase {

	function get_name() {
    
		return GD_DATALOADER_COMMENTLIST;
	}

	function get_query($query_args) {
	
		$query = parent::get_query($query_args);

		$query['status'] = 'approve';
		$query['type'] = 'comment'; // Only comments, no trackbacks or pingbacks
		$query['post_id'] = $query_args[GD_URLPARAM_POSTID];

		return $query;
	}
	
    function execute_query($query) {
    
    	$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
    	return $cmsapi->get_comments($query);
	}

	function execute_query_ids($query) {
    
    	$ret = array();
    	$comments = $this->execute_query($query);
		foreach ($comments as $comment) {
			$ret[] = $comment->comment_ID;
		}

		return $ret;
	}
}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_CommentList();