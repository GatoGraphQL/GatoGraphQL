<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_TemplateResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function init() {

		parent::init();

		// In addition only for the templates, create a mapping between template name and resources,
		// so that from template-sources we can obtain directly what are those resources 
		global $pop_templateresourceloaderprocessor_manager;
		$pop_templateresourceloaderprocessor_manager->add($this, $this->get_resources_to_process());
	}

	// protected function get_manager() {

	// 	global $pop_templateresourceloaderprocessor_manager;
	// 	return $pop_templateresourceloaderprocessor_manager;
	// }
	
	function get_suffix($resource) {
	
		return '.tmpl.js';
	}
	
	protected function can_defer($resource) {

		// Javascript Template files can only be deferred when doing server-side rendering
		return PoP_Frontend_ServerUtils::use_serverside_rendering();
	}
	
	// function get_htmltag_attributes($resource) {

	// 	// When first loading the website, if doing serverside-rendering, then most of the javascript template files
	// 	// are actually not needed. They are needed only when the block is lazy-loaded, or when performing a dynamic action,
	// 	// such as showing the events calendar (rendered through JS) or appending more posts to the feed
	// 	if (PoP_Frontend_ServerUtils::use_serverside_rendering() && PoP_Frontend_ServerUtils::use_code_splitting()) {

	// 		$engine = PoP_Engine_Factory::get_instance();
	// 		$json = $engine->resultsObject['json'];
	// 		if ($dynamic_template_sources = $json['sitemapping']['dynamic-template-sources']) {
				
	// 			if (!in_array($this->get_filename($resource), $dynamic_template_sources)) {

	// 				return "async='async'";
	// 			}
	// 		}
	// 	}

	// 	return parent::get_htmltag_attributes($resource);
	// }
	
	// function is_async($resource) {
	function is_defer($resource, $vars_hash_id) {

		// When first loading the website, if doing serverside-rendering, then most of the javascript template files
		// are actually not needed. They are needed only when the block is lazy-loaded, or when performing a dynamic action,
		// such as showing the events calendar (rendered through JS) or appending more posts to the feed
		if (/*PoP_Frontend_ServerUtils::use_serverside_rendering() && */PoP_Frontend_ServerUtils::use_code_splitting()) {

			// Comment Leo 05/12/2017: instead of checking from the current dynamic-template-sources, get the value from the resource-cache,
			// so that it also works for when generating the bundle(group) files during the /generate-theme/ process
			// $engine = PoP_Engine_Factory::get_instance();
			// $json = $engine->resultsObject['json'];
			// $dynamic_template_sources = $json['sitemapping']['dynamic-template-sources'];
			// global $pop_resourceloader_resourcecachemanager;
			// $dynamic_template_sources = $pop_resourceloader_resourcecachemanager->get_dynamic_template_sources($vars_hash_id);
			global $gd_template_memorymanager;
			if ($dynamic_template_sources = $gd_template_memorymanager->get_cache($vars_hash_id, POP_MEMORYTYPE_DYNAMICTEMPLATERESOURCES, true)) {
				
				// Comment Leo 20/11/2017: taking a very aggressive approach: make all templates be deferred,
				// unless they are inside a dynamic component (this could also be made deferred, but to be on the safe side,
				// as in any JS method needing a .tmpl template being set as 'critical', then keep it like this)
				return !in_array($this->get_filename($resource), $dynamic_template_sources);
			}
		}

		// return parent::is_async($resource);
		return parent::is_defer($resource, $vars_hash_id);
	}
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);

		// All templates depend on the handlebars runtime. Allow plugins to add their own dependencies
		if ($template_dependencies = apply_filters(
			'PoP_TemplateResourceLoaderProcessor:dependencies',
			array(
				POP_RESOURCELOADER_EXTERNAL_HANDLEBARS,
				POP_RESOURCELOADER_HELPERSHANDLEBARS,
			)
		)) {
			$dependencies = array_merge(
				$dependencies,
				$template_dependencies
			);
		}
	
		return $dependencies;
	}
		
	function extract_mapping($resource) {
	
		return false;
	}
	
	// function is_extension($resource) {

	// 	return false;
	// }
	
	// function get_filename($resource) {
	
	// 	return PoP_ResourceLoaderProcessorUtils::get_template_source($resource).$this->get_suffix($resource);
	// }
}