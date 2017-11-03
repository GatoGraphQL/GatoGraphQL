<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_EVENT', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-event'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_PASTEVENT', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-pastevent'));

class GD_EM_Template_Processor_PostMultipleSidebarComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_EVENT,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_PASTEVENT,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_EVENT:

				$ret[] = GD_TEMPLATE_EM_WIDGETCOMPACT_EVENTINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_PASTEVENT:

				$ret[] = GD_TEMPLATE_EM_WIDGETCOMPACT_PASTEVENTINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_PostMultipleSidebarComponents();