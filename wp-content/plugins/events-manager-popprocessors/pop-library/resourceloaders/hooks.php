<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class EM_PoPProcessors_ResourceLoaderProcessor_Hooks {

	function __construct() {

		add_filter(
			'PoP_TemplateResourceLoaderProcessor:dependencies',
			array($this, 'get_template_dependencies')
		);
	}

	function get_template_dependencies($dependencies) {

		// Add the Handlebars Helpers file
		$dependencies[] = POP_RESOURCELOADER_EMHANDLEBARSHELPERS;
		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new EM_PoPProcessors_ResourceLoaderProcessor_Hooks();
