<?php
namespace PoP\Engine;

abstract class TagFilterBase extends FilterBase {

	function get_filter_args_override_values() {
	
		$args = parent::get_filter_args_override_values();

		if (!($filtercomponents = $this->get_filtercomponents())) {
			return $args;
		}
		
		// Tag Search (for function get_tags: https://codex.wordpress.org/Function_Reference/get_tags)
		if ($search = $this->get_search()) {
			$args['search'] = $search;
		}

		// Order / Orderby
		if ($order = $this->get_order()) {

			$args['orderby'] = $order['orderby'];
			$args['order'] = $order['order'];
		}
		
		return $args;
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
		
			if ($maybe_search = $filtercomponent->get_search($this)) {
				
				$search = $maybe_search;
				
				// Only 1 filter can do the Search, so already break
				break;
			}		
		}
		
		return $search;	
	}
}
