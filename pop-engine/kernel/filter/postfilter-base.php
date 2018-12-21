<?php
namespace PoP\Engine;

abstract class PostFilterBase extends FilterBase {

	function get_filter_args_override_values() {
	
		$args = parent::get_filter_args_override_values();

		if (!($filtercomponents = $this->get_filtercomponents())) {
			return $args;
		}
		
		// Post status
		if ($status = $this->get_poststatus()) {
			$args['post_status'] = $status;
		}
		
		// Search
		if ($search = $this->get_search()) {
			$args['is_search'] = true;
			$args['s'] = $search;
		}

		// Tags
		if ($tags = $this->get_tags()) {
			$args['tag'] = implode(',', $tags);
		}

		// Categories
		if ($categories = $this->get_categories()) {
			$args['cat'] = implode(',', $categories);
		}

		// Taxonomies
		if ($taxonomyterms = $this->get_taxonomies()) {

			// The format of the taxonomies now is array($taxonomy => $terms)
			// Convert it to an array like this: array('taxonomy' => $taxonomy, 'terms' => $terms)
			$taxqueries = array();
			foreach ($taxonomyterms as $taxonomy => $terms) {
				$taxqueries[] = array(
					'taxonomy' => $taxonomy,
					'terms' => $terms,
				);
			}
			$args['tax_query'] = array_merge(
				array(
					'relation' => 'OR'
				),
				$taxqueries
			);
		}

		// Order / Orderby
		if ($order = $this->get_order()) {

			$args['orderby'] = $order['orderby'];
			$args['order'] = $order['order'];
		}

		// Author
		if ($author = $this->get_author()) {
			$args['author'] = implode(",", $author);
		}
		
		return $args;
	}

	function get_filter_args() {
	
		$args = parent::get_filter_args();
		
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return $args;
		}
								
		// Meta query
		if ($meta_query = $this->get_metaquery()) {
			$args['meta_query'] = $meta_query;
		}
		
		return $args;
	}
	
	function get_metaquery() {
	
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}

		$meta_query = array();
		foreach ($filtercomponents as $filtercomponent) {
		
			if ($filtercomponent_metaquery = $filtercomponent->get_metaquery($this)) {
			
				$meta_query = array_merge($meta_query, $filtercomponent_metaquery);
			}
		}
		
		if ($meta_query) {
		
			// When filtering users, it will bring them duplicated. Solution: hook "found_users_query_avoid_duplicates" in users.php
			$meta_query['relation'] = 'AND';
		}
		return $meta_query;	
	}
	
	function get_author() {
	
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}

		$author = array();
		foreach ($filtercomponents as $filtercomponent) {

			if ($filtercomponent_author = $filtercomponent->get_author($this)) {

				$author = array_merge($author, $filtercomponent_author);
			}
		}
		
		return $author;	
	}

	function get_poststatus() {
	
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}

		$status = array();
		foreach ($filtercomponents as $filtercomponent) {
		
			if ($filtercomponent_poststatus = $filtercomponent->get_poststatus($this)) {
				
				$status = array_merge($status, $filtercomponent_poststatus);
			}
		}
		
		return $status;	
	}

	function get_categories() {
	
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}

		$categories = array();
		foreach ($filtercomponents as $filtercomponent) {
		
			if ($filtercomponent_categories = $filtercomponent->get_categories($this)) {
			
				$categories = array_merge($categories, $filtercomponent_categories);
			}
		}
		
		return $categories;	
	}

	function get_taxonomies() {
	
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}

		$taxonomies = array();
		foreach ($filtercomponents as $filtercomponent) {
		
			// array_merge_recursive: it allows the terms to be merged together into a single array under the same taxonomy
			// Eg: if we get array('category' => array(1, 3, 4)) and array('category' => array(2, 5)), the result will be array('category' => array(1, 2, 3, 4, 5))
			$taxonomies = array_merge_recursive($taxonomies, $filtercomponent->get_taxonomies($this));
		}
		
		return $taxonomies;	
	}

	function get_tags() {
	
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}

		$tags = array();
		foreach ($filtercomponents as $filtercomponent) {
		
			if ($filtercomponent_tags = $filtercomponent->get_tags($this)) {

				$tags = array_merge($tags, $filtercomponent_tags);
			}
		}
		
		return $tags;	
	}

	function get_order() {
	
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}

		$order = array();
		foreach ($filtercomponents as $filtercomponent) {

			if ($order = $filtercomponent->get_order($this)) {
				
				// Only 1 filter can define the Order, so already break
				break;
			}
		}
		
		return $order;	
	}
	
	function get_search() {
	
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}

		$search = '';
		foreach ($filtercomponents as $filtercomponent) {
		
			if ($search = $filtercomponent->get_search($this)) {
				
				// Only 1 filter can do the Search, so already break
				break;
			}		
		}
		
		return $search;	
	}
	
	function get_postdates() {
		
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}

		$postdates = array();
		foreach ($filtercomponents as $filtercomponent) {
		
			if ($postdates = $filtercomponent->get_postdates($this)) {
				
				// Only 1 filter can define the post dates, so already break
				break;
			}			
		}

		return $postdates;
	}

	function filter_where( $where = '' ) {

		$postdates = $this->get_postdates();

		if ($postdates['from']) {
			$where .= " AND post_date >= '".$postdates['from']."'";
		}

		if ($postdates['to']) {
			$where .= " AND post_date < '".$postdates['to']."'";				
		}

		return $where;
	}
}
