<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_TemplateResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	function init() {

		parent::init();

		// In addition only for the templates, create a mapping between template name and resources,
		// so that from template-sources we can obtain directly what are those resources 
		global $pop_templateresourceloaderprocessor_manager;
		$pop_templateresourceloaderprocessor_manager->add($this, $this->get_resources_to_process());
	}
	
	function get_suffix($resource) {
	
		return '.tmpl.js';
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
	
	// function is_extension($resource) {

	// 	return false;
	// }
	
	// function get_filename($resource) {
	
	// 	return PoP_ResourceLoaderProcessorUtils::get_template_source($resource).$this->get_suffix($resource);
	// }
}