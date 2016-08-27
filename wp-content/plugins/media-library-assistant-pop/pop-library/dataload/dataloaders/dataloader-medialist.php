<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Ajax Load Posts Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_MEDIALIST', 'media-list');
define ('GD_DATABASE_KEY_MEDIA', 'media');

class GD_DataLoader_MediaList extends GD_DataLoader_PostListBase {

	function get_name() {
    
		return GD_DATALOADER_MEDIALIST;
	}

    function get_execution_priority() {
    
		return 1;
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_MEDIA;
	}
	
	function get_query($vars = array()) {
	
		$query = parent::get_query($vars);

		$query['post_parent'] = 'all';

		// Execute after filter.php function filter_query
		add_filter( 'gd_dataload_pre_execute', array(&$this, 'get_after_filter_query'), 20, 3 );
		
		return $query;
	}

	function get_after_filter_query($query, $is_main_query, $vars = array()) {
	
		if (!$is_main_query)
			return $query;	

		// Only after applying the filter, check if 'post_mime_type' is still not set-up, if so add the default value
		// This can't be done in "function get_query" because the array_merge_recursive in filter.php function filter_query
		// Will duplicate the entry and generate an array instead of a string when 2 values are filled in
		if (!$query['post_mime_type']) {
			$query['post_mime_type'] = GD_MEDIA_TYPE_ALL;
		}

		// Filter "Is Resource" on My Resources page can set the taxonomy, so check if it was not done already
		if (!$query[GD_MLA_MEDIA_TAXONOMY]) {
			
			// Show only Resources
			if ($taxonomy = $vars['taxonomy']) {
				$term = get_term($taxonomy, GD_MLA_MEDIA_TAXONOMY);
				$cat_slug = $term->slug;	
				$query[GD_MLA_MEDIA_TAXONOMY] = $cat_slug;
			}
		}

		// Remove this filter so it doesn't execute anymore with the other dataloaders
		remove_filter( 'gd_dataload_pre_execute', array(&$this, 'get_after_filter_query'), 20, 3 );

		return $query;
	}
	
    function execute_query($query) {

    	return MLAShortcodes::mla_get_shortcode_attachments(0, $query);
	}
	
	
	function get_data_query($ids) {
    
		$query = array(
			'ids' => $ids,
			'post_mime_type' => GD_MEDIA_TYPE_ALL
		);

		return $query;
	}
	
	function execute_query_ids($query) {
    
    	$query['fields'] = 'ids';
    	return $this->execute_query($query);
	}
	
	function get_database_key() {
	
		// Comment Leo 27/08/2014
		// Commented since introducing Block "Latest Everything", where media is also fetched and displayed under 'posts', so unifying everything now
		// return GD_DATABASE_KEY_MEDIA;
		return GD_DATABASE_KEY_POSTS;
	}			
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_MediaList();