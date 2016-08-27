<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Ajax Load Posts Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_POSTLIST', 'post-list');

class GD_DataLoader_PostList extends GD_DataLoader_PostListBase {

	function get_name() {
    
		return GD_DATALOADER_POSTLIST;
	}

    function get_execution_priority() {
    
		return 1;
	}
	
	function get_query($vars = array()) {
	
		$query = parent::get_query($vars);

		// Category
		if ($cat = $vars['cat']) {
	
			$query['cat'] = $cat;
		}

		// Tags
		if ($tag_id = $vars['tag-id']) {
	
			$query['tag_id'] = $tag_id;
		}
		if ($tag = $vars['tag']) {
	
			$query['tag'] = $tag;
		}
	
		return $query;
	}
	
    function execute_query($query) {

    	return get_posts($query);
	}
	
	function execute_query_ids($query) {
    
    	$query['fields'] = 'ids';
    	return $this->execute_query($query);
	}
	
	function get_database_key() {
	
		return GD_DATABASE_KEY_POSTS;
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_PostList();