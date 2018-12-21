<?php
namespace PoP\Engine;

class Filter_Manager {

	var $filters;
	
	function __construct() {
	
		$this->filters = array();
	}
	
	function add($filter) {
	
		$this->filters[$filter->get_name()] = $filter;
	}

	function get_filter($filtername) {
	
		return $this->filters[$filtername];
	}

	/**
	 * Returns the currently used filter
	 */
	function get_filteringby_filter() {
	
		$filtername = $_REQUEST[GD_FILTER_FILTERING_FIELD];
		return $this->get_filter($filtername);
	}
	
	/**
	 * Returns true if it is filtering by the given $filter
	 */
	function filteringby($filter_or_filtername) {

		if (is_object($filter_or_filtername)) {

			$filter = $filter_or_filtername;
		}
		else {

			global $gd_filter_manager;
			$filtername = $filter_or_filtername;
			$filter = $gd_filter_manager->get_filter($filtername);
		}
	
		$wildcard_filters = array();
		$wildcardfilter = $filter;
		
		// Wildcard filters: both filters and wildcard filters can specify, so bring all of them down the road
		// while ($wildcardfilter = $wildcardfilter->get_wildcard_filter()) {
		while ($wildcardfilter_name = $wildcardfilter->get_wildcard_filter()) {

			$wildcard_filters[] = $wildcardfilter_name;
			$wildcardfilter = $this->get_filter($wildcardfilter_name);
		}

		// Filtering by the same Filter or its wildcard (eg: a General Post Search is a wildcard, it involves all posts filters like Announcements Filter)
		$requested_filtername = $_REQUEST[GD_FILTER_FILTERING_FIELD];
		return ($requested_filtername == $filter->get_name() || (!empty($wildcard_filters) && in_array($requested_filtername, $wildcard_filters)));
	}

	function filter_query($query, $data_properties) {

		// Allow $gd_filter to filter the query
		$query = apply_filters('filter_query', $query, $data_properties);
	
		// Validate currently filtering by this blocks' filter
		$filter_name = $data_properties[GD_DATALOAD_FILTER];
		if (!$filter_name || !$this->filteringby($filter_name)) {

			return $query;
		}

		global $gd_filter_manager;
		$filter = $gd_filter_manager->get_filter($filter_name);
		
		// Merge with the filter query filters
		// Using array_merge_recursive on 'meta-query', which can be set in different places (in the filter, eg: when filtering Action category)
		// (and in the Action map to filter all Actions which have a location)
		// Important: Make sure there is not empty string in the query! (Eg: 'author' => '') because with array_merge_recursive
		// will also process that one element, making an array when it should be a string

		$query = array_merge_recursive($query, $filter->get_filter_args($data_properties));
		$query = array_merge($query, $filter->get_filter_args_override_values($data_properties));

		// If both "tag" and "tax_query" were set, then the filter will not work for tags
		// Instead, what it requires is to create a nested taxonomy filtering inside the tax_query,
		// including both the tag and the already existing taxonomy filtering (eg: categories)
		// So make that transformation (https://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters)
		$vars = Engine_Vars::get_vars();
		if (($vars['global-state']['is-tag'] || $query['tag']) && $query['tax_query']) {

			// Create the tag item in the taxonomy
			$tag_slug = '';
			if ($vars['global-state']['is-tag']) {
				$tag = $vars['global-state']['queried-object'];
			    $tag_slug = $tag->slug;
			}
			else {
				$tag_slug = $query['tag'];
			}
			$tag_item = array(
				'taxonomy' => 'post_tag',
				'terms' => explode(',', $tag_slug),
				'field' => 'slug'
			);

			// Will replace the current tax_query with a new one
			$tax_query = $query['tax_query'];
			$new_tax_query = array(
				'relation' => 'AND',//$tax_query['relation']
			);
			unset($tax_query['relation']);
			foreach ($tax_query as $tax_item) {
				$new_tax_query[] = array(
					// 'relation' => 'AND',
					$tax_item,
					$tag_item,
				);
			}
			$query['tax_query'] = $new_tax_query;

			// The tag arg is not needed anymore
			if ($query['tag']) {
				unset($query['tag']);
			}
		}
		$filter->filter_query($query, $data_properties);

		// Allow for date filtering
		$query['suppress_filters'] = false;		// Allow for 'posts_where' filter to be called: http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
		add_filter( 'posts_where', array(&$filter, 'filter_where') );	

		return $query;
	}
	
	function clear_filter() {

		// Allow $gd_filter to clear (remove unneeded filters)
		do_action('clear_filter');

		if (!($filter = $this->get_filteringby_filter())) {
			return;	
		}

		// Remove the filters
		remove_filter( 'posts_where', array(&$filter, 'filter_where') );		
	}

	/**
	 * Adds the params to filter to the url
	 */
	function add_filter_params($url, $filter, $args = array()) {
	
		// Add the 'filter' param
		$url = add_query_arg(GD_FILTER_FILTERING_FIELD, $filter->get_name(), $url);
		foreach ($args as $field => $value) {
		
			$url = add_query_arg($field, $value, $url);
		}
		
		return $url;
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filter_manager;
$gd_filter_manager = new Filter_Manager();
