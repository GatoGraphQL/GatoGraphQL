<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_DATALOADER_COMMENTLIST', 'comments-list');

// We are assuming we are in either page or single templates
class GD_DataLoader_CommentList extends GD_DataLoader_List {

	function get_name() {
    
		return GD_DATALOADER_COMMENTLIST;
	}

	function get_dataquery() {

		return GD_DATAQUERY_COMMENT;
	}

	function get_query($vars = array()) {
	
		$query = parent::get_query($vars);

		$main_vars = GD_TemplateManager_Utils::get_vars();
		$paged = $vars[GD_URLPARAM_PAGED];
		$limit = $vars[GD_URLPARAM_LIMIT];

		if (GD_TemplateManager_Utils::loading_latest()) {
			$paged = 1;
			$limit = 0;
		}

		// Params and values by default		
		if (isset( $vars['orderby'] )) {
			$query['orderby'] = $vars['orderby'];
		}
		if (isset( $vars['order'] )) {
			$query['order'] =  $vars['order'];
		}
		if ($limit >= 1) {

			$offset = ($paged - 1) * $limit;

			// $query[GD_URLPARAM_PAGED] = $paged;
			$query['offset'] = $offset;
			$query['number'] = $limit;
		}

		$post = $main_vars['global-state']['post']/*global $post*/;
		$query['status'] = 'approve';
		$query['type'] = 'comment'; // Only comments, no trackbacks or pingbacks
		$query['post_id'] = $vars[GD_URLPARAM_POSTID];

		return $query;
	}
	
    function execute_query($query) {
    
    	return get_comments($query);
	}

	function execute_query_ids($query) {
    
    	$ret = array();
    	$comments = $this->execute_query($query);
		foreach ($comments as $comment) {
			$ret[] = $comment->comment_ID;
		}

		return $ret;
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_COMMENT;
	}
	
	/**
     * Function to override
     */
	function execute_get_data($ids) {
	
		$ret = array();
		foreach ($ids as $id) {
			$ret[] = get_comment($id);
		}

		return $ret;
	}
	
	function get_execution_priority() {

		return 2;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_COMMENTS;
	}

}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_CommentList();