<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Users Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_TAGLIST', 'tag-list');

class GD_DataLoader_TagList extends GD_DataLoader_List {

	function get_name() {
    
		return GD_DATALOADER_TAGLIST;
	}

	function get_dataquery() {

		return GD_DATAQUERY_TAG;
	}

	function get_data_query($ids) {
    
		$query = array(
			'include' => implode(', ', $ids)
		);
		return $query;
	}
	
	function get_execution_priority() {
    
		return 1;
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_TAG;
	}

	function get_query($vars = array()) {
	
		$query = parent::get_query($vars);

		$paged = $vars[GD_URLPARAM_PAGED];
		$limit = $vars[GD_URLPARAM_LIMIT];

		if (GD_TemplateManager_Utils::loading_latest()) {
			$paged = 1;
			$limit = 0;
		}

		if ($limit >= 1) {

			$offset = ($paged - 1) * $limit;

			$query['offset'] = $offset;
			$query['number'] = $limit;
		}

		$query['orderby'] = isset( $vars['orderby'] ) ? $vars['orderby'] : 'name';
		$query['order'] = isset( $vars['order'] ) ? $vars['order'] : 'ASC';

		if ($meta_query = $vars['meta-query']) {
			$query['meta_query'] = $meta_query;
		}
		
		return $query;
	}
	
    function execute_query($query) {

    	return get_tags($query);
	}
	
	function execute_query_ids($query) {
    
    	// Retrieve only ids
		$query['fields'] = 'ids';
    	return $this->execute_query($query);
	}	

	function get_database_key() {
	
		return GD_DATABASE_KEY_TAGS;
	}

}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_TagList();