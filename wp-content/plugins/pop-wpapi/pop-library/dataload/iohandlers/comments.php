<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_LIST_COMMENTS', 'comment-list');

class GD_DataLoad_IOHandler_CommentList extends GD_DataLoad_IOHandler_List {

	function get_name() {
    
		return GD_DATALOAD_IOHANDLER_LIST_COMMENTS;
	}

	function get_vars($atts, $iohandler_atts) {
    
		$ret = parent::get_vars($atts, $iohandler_atts);

		if (isset($atts[GD_URLPARAM_POSTID])) {
			$ret[GD_URLPARAM_POSTID] = $atts[GD_URLPARAM_POSTID];	
		}
		else {
			// By default, select the global $post ID;
			global $post;
			$ret[GD_URLPARAM_POSTID] = $post->ID;	
		}

		// Limit: by default, show all comments
		$ret[GD_URLPARAM_LIMIT] = isset($atts[GD_URLPARAM_LIMIT]) ? $atts[GD_URLPARAM_LIMIT] : 0;

		// The Order must always be date > ASC so the jQuery works in inserting sub-comments in already-created parent comments
		$ret['order'] =  'ASC';
		$ret['orderby'] =  'comment_date_gmt';

		return $ret;
	}

	function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		$vars = $this->get_vars($vars_atts, $iohandler_atts);

		// Add the post_id, so we know what post to fetch comments from when filtering
		$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_POSTID] = $vars[GD_URLPARAM_POSTID];

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_CommentList();
