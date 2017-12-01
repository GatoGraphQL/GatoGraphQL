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
	
	function get_scripttag_attributes($resource) {

		if ($this->is_async($resource)) {

			return "async='async'";
		}
		// can_defer: allows the templates to check if we are doing serverside-rendering, because .tmpl files cannot be made "defer" when doing client-side rendering
		elseif ($this->can_defer($resource) && $this->is_defer($resource)) {

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
	
	function is_defer($resource) {

		// If these resources have been marked as 'noncritical', then defer loading them
		if (PoP_Frontend_ServerUtils::use_progressive_booting() && in_array($resource, PoP_ResourceLoaderProcessorUtils::get_noncritical_resources())) {

			return true;
		}

		return false;
	}
	
	function async_load_in_order($resource) {

		return false;
	}
}