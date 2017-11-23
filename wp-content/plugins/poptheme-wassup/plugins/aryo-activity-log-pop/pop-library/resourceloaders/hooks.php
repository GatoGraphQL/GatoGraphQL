<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PopThemeWassup_AAL_ResourceLoaderProcessor_Hooks {

	function __construct() {

		add_filter(
			'GD_Template_ProcessorBase:template-resources',
			array($this, 'get_template_css_resources'),
			10,
			5
		);
	}

	function get_template_css_resources($resources, $template_id, $template_source, $atts, $processor) {

		switch ($template_source) {
			
			case GD_TEMPLATESOURCE_LAYOUT_PREVIEWNOTIFICATION:

				$resources[] = POP_RESOURCELOADER_CSS_NOTIFICATIONLAYOUT;
				break;
		}

		return $resources;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PopThemeWassup_AAL_ResourceLoaderProcessor_Hooks();
