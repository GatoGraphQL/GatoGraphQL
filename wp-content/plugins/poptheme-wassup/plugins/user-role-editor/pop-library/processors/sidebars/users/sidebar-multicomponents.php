<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_ORGANIZATION', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-organization'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_INDIVIDUAL', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-individual'));

class GD_URE_Custom_Template_Processor_UserMultipleSidebarComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_ORGANIZATION,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_INDIVIDUAL,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_ORGANIZATION:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_ORGANIZATIONINFO;
				$ret[] = GD_URE_TEMPLATE_WIDGETCOMPACTWRAPPER_COMMUNITIES;
				
				// Show the Author Description inside the widget instead of the body?
				if (!PoPTheme_Wassup_Utils::author_fulldescription()) {
					$ret[] = GD_TEMPLATE_WIDGETCOMPACT_AUTHORDESCRIPTION;
				}
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_INDIVIDUAL:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_INDIVIDUALINFO;
				$ret[] = GD_URE_TEMPLATE_WIDGETCOMPACTWRAPPER_COMMUNITIES;
				
				// Show the Author Description inside the widget instead of the body?
				if (!PoPTheme_Wassup_Utils::author_fulldescription()) {
					$ret[] = GD_TEMPLATE_WIDGETCOMPACT_AUTHORDESCRIPTION;
				}
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_Template_Processor_UserMultipleSidebarComponents();