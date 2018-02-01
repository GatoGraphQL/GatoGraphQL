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

		add_filter(
			'GD_Template_ProcessorBase:template-resources',
			array($this, 'get_template_css_resources'),
			10,
			4
		);
	}

	function get_template_css_resources($resources, $template_id, $template_source, $atts) {

		switch ($template_source) {

			case GD_TEMPLATESOURCE_FORMCOMPONENT_DATERANGE:

				$resources[] = POP_RESOURCELOADER_EXTERNAL_CSS_DATERANGEPICKER;
				break;
		}

		return $resources;
	}

	function get_template_dependencies($dependencies) {

		// Add the Handlebars Helpers file
		$dependencies[] = POP_RESOURCELOADER_COREHANDLEBARSHELPERS;
		return $dependencies;
	}

	function get_manager_dependencies($dependencies) {

		// User logged-in styles
		$dependencies[] = POP_RESOURCELOADER_CSS_USERLOGGEDIN;

		// This dependency has been added, because we are referencing function 'mentions_source' in editor.php
		// Even though popMentions will be loaded from the internal/external method calls, if the mapping has not
		// been generated, then it will fail and give a JS error. By expliciting the dependency here, it will work always fine
		$dependencies[] = POP_RESOURCELOADER_MENTIONS;

		// Comment Leo 19/11/2017: load the appshell file if either: enabling the config, using the appshell, or allowing for Service Workers, which use the appshell to load content when offline
		if (PoP_ServerUtils::enable_config_by_params() || PoP_Frontend_ServerUtils::use_appshell() || PoP_Frontend_ServerUtils::use_serviceworkers()) {

			$dependencies[] = POP_RESOURCELOADER_APPSHELL;
		}
		
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
