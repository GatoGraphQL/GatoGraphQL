<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoader_PostListBase extends GD_DataLoader_List {

	function get_dataquery() {

		return GD_DATAQUERY_POST;
	}
	
	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_POST;
	}
	
    /**
     * Function to override
     */
    function get_data_query($ids) {
    
    	$query = parent::get_data_query($ids);
		$query['include'] = $ids;
		$query['post_status'] = 'any'; // Post status can also be 'pending', so don't limit it here, just select by ID
		// $query['post_type'] = apply_filters('gd_dataloader_list:query:post_types', array('post'));
		$query['post_type'] = gd_dataload_posttypes(); // Allow also Events post types, so these can be fetched from Stories (field references)
		
		return $query;
	}		
	
	/**
     * Function to override
     */
    function get_query($vars = array()) {
    
		$query = parent::get_query($vars);
		
		$paged = $vars[GD_URLPARAM_PAGED];
		$limit = $vars[GD_URLPARAM_LIMIT];

		if (GD_TemplateManager_Utils::loading_latest()) {
			$paged = 1;
			$limit = -1;

			// Return the posts created after the given timestamp
			$timestamp = $vars[GD_URLPARAM_TIMESTAMP];
			$query['date_query'] = array(
				array(
					'after' => date('Y-m-d H:i:s', $timestamp),
					'inclusive' => false,
				)
			);
		}

		// Params and values by default		
		$orderby = isset( $vars['orderby'] ) ? $vars['orderby'] : 'date';
		$order = isset( $vars['order'] ) ? $vars['order'] : 'DESC';
		
		// Most basic query variables
		// $query = array(
		// 	'orderby' => $orderby,
		// 	'order' => $order
		// );
		$query['orderby'] = $orderby;
		$query['order'] = $order;
		
		if ($author = $vars['author']) {
			$query['author'] = $author;
		}

		if ($tag = $vars['tag']) {
			$query['tag'] = $tag;
		}

		// post__not_in to remove posts in the Hierarchy (eg: remove current Single post for Block Related)
		if ($post_not_in = $vars['post__not_in']) {

			$query['post__not_in'] = $post_not_in;
		}

		if ($limit >= 1) {

			$offset = ($paged - 1) * $limit;

			$query[GD_URLPARAM_PAGED] = $paged;
			$query['offset'] = $offset;
			$query['posts_per_page'] = $limit;
		}
		else {
			// $limit is 0 (EM) or -1 (WP)
			$query['numberposts'] = $limit;
		}

		// Metaquery: eg: filter only Actions with Locations
		if ($meta_query = $vars['meta-query']) {
			$query['meta_query'] = $meta_query;
		}

		// Tax Query: eg: to bring all different content for the Latest Everything Block
		if ($post_type = $vars['post-type']) {
			$query['post_type'] = $post_type;
		}
		if ($tax_query = $vars['tax-query']) {
			$query['tax_query'] = $tax_query;
		}

		// Post status: added for selecting the ID / Nonce of a newly created post (which is 'pending')
		if ($post_status = $vars['post-status']) {
			$query['post_status'] = $post_status;
		}		

		// Allow for co-authors plus plug-in to add "posts_where_filter"
		$query['suppress_filters'] = false;

		return $query;
	}

}
	
