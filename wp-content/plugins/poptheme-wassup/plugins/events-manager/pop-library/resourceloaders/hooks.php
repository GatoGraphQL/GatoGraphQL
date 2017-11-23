<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_EM_ResourceLoaderProcessor_Hooks {

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
			
			case GD_TEMPLATESOURCE_MAP_DIV:

				$resources[] = POP_RESOURCELOADER_CSS_MAP;
				break;
		}

		// Artificial property added to identify the template when adding template-resources
		if ($resourceloader_att = $processor->get_att($template_id, $atts, 'resourceloader')) {

			if ($resourceloader_att == 'map' || $resourceloader_att == 'calendarmap') {

				$resources[] = POP_RESOURCELOADER_CSS_MAP;
			}
			elseif ($resourceloader_att == 'calendar' || $resourceloader_att == 'calendarmap') {

				$resources[] = POP_RESOURCELOADER_CSS_CALENDAR;
			}
		}

		return $resources;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_EM_ResourceLoaderProcessor_Hooks();
