<?php
namespace PoP\CMS\WP;

class HooksAPI extends \PoP\CMS\HooksAPI_Base implements \PoP\CMS\HooksAPI
{
	public function addFilter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
		return add_filter($tag, $function_to_add, $priority, $accepted_args);
	}
    public function removeFilter($tag, $function_to_remove, $priority = 10) {
    	return remove_filter($tag, $function_to_remove, $priority);
    }
    public function applyFilters($tag, $value, ...$args) {
    	return apply_filters($tag, $value, ...$args);
    }
    public function addAction($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
    	return add_action($tag, $function_to_add, $priority, $accepted_args);
    }
    public function removeAction($tag, $function_to_remove, $priority = 10) {
    	return remove_action($tag, $function_to_remove, $priority);
    }
    public function doAction($tag, ...$args) {
    	return do_action($tag, ...$args);
    }
}

/**
 * Initialize
 */
new HooksAPI();
