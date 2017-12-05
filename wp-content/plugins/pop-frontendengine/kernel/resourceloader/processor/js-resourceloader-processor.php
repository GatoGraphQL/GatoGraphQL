<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_JSResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	// protected function get_manager() {

	// 	global $pop_jsresourceloaderprocessor_manager;
	// 	return $pop_jsresourceloaderprocessor_manager;
	// }
	function init() {

		parent::init();

		global $pop_jsresourceloaderprocessor_manager;
		$pop_jsresourceloaderprocessor_manager->add($this, $this->get_resources_to_process());
	}
	
	function get_type($resource) {
	
		return POP_RESOURCELOADER_RESOURCETYPE_JS;
	}
	
	function get_suffix($resource) {
	
		return (PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '').'.js';
	}

	function in_footer($resource) {
	
		return true;
	}

	function get_jsobjects($resource) {

		return array();
	}
	
	function get_globalscope_method_calls($resource) {
	
		return array();
	}
	
	function get_scripttag_attributes($resource, $vars_hash_id) {

		if ($this->is_async($resource)) {

			return "async='async'";
		}
		// can_defer: allows the templates to check if we are doing serverside-rendering, because .tmpl files cannot be made "defer" when doing client-side rendering
		elseif ($this->can_defer($resource) && $this->is_defer($resource, $vars_hash_id)) {

			return "defer='defer'";
		}

		// return parent::get_htmltag_attributes($resource);
		return '';
	}
	
	function is_async($resource) {

		return false;
	}
	
	protected function can_defer($resource) {

		// can_defer: allows the templates to check if we are doing serverside-rendering, because .tmpl files cannot be made "defer" when doing client-side rendering
		return true;
	}
	
	// is_defer function is relative to the specific $vars, since a resource may be defer for a page, but not for another
	// This is evident when generating all the bundle(group) files, in which is_defer is accessed to calculate the deferred bundle(group)s
	function is_defer($resource, $vars_hash_id) {

		// If these resources have been marked as 'noncritical', then defer loading them
		if (PoP_Frontend_ServerUtils::use_progressive_booting()) {

			// global $pop_resourceloader_resourcecachemanager;
			// $noncritical_resources = $pop_resourceloader_resourcecachemanager->get_noncritical_resources($vars_hash_id);
			// $noncritical_resources = PoP_ResourceLoaderProcessorUtils::get_noncritical_resources();
			global $gd_template_memorymanager;
			if ($noncritical_resources = $gd_template_memorymanager->get_cache($vars_hash_id, POP_MEMORYTYPE_NONCRITICALRESOURCES, true)) {
				
				return in_array($resource, $noncritical_resources);
			}
		}

		return false;
	}
	
	function async_load_in_order($resource) {

		return false;
	}
}