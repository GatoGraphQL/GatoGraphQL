<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_CoreProcessors_ResourceLoaderProcessor_Hooks {

	function __construct() {

		add_filter(
			'PoP_TemplateResourceLoaderProcessor:dependencies',
			array($this, 'get_template_dependencies')
		);
		add_filter(
			'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
			array($this, 'get_manager_dependencies')
		);
		add_filter(
			'PoP_ResourceLoader_FileReproduction_InitialResourcesConfig:pages',
			array($this, 'get_initial_pages')
		);
	}

	function get_template_dependencies($dependencies) {

		// Add the Handlebars Helpers file
		$dependencies[] = POP_RESOURCELOADER_COREHANDLEBARSHELPERS;
		return $dependencies;
	}

	function get_manager_dependencies($dependencies) {

		// This dependency has been added, because we are referencing function 'mentions_source' in editor.php
		// Even though popMentions will be loaded from the internal/external method calls, if the mapping has not
		// been generated, then it will fail and give a JS error. By expliciting the dependency here, it will work always fine
		$dependencies[] = POP_RESOURCELOADER_MENTIONS;
		return $dependencies;
	}

	function get_initial_pages($pages) {

		$pages[] = POP_COREPROCESSORS_PAGE_LOGGEDINUSERDATA;
		return $pages;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CoreProcessors_ResourceLoaderProcessor_Hooks();
