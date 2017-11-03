<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_GENERIC', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-generic'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_AVATAR', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-avatar'));

class GD_Custom_Template_Processor_UserMultipleSidebarComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_GENERIC,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_AVATAR,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_GENERIC:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO;
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_AVATAR:

				$ret[] = GD_TEMPLATE_LAYOUT_AUTHOR_USERPHOTO;
				$ret[] = GD_TEMPLATE_USERSOCIALMEDIA;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_UserMultipleSidebarComponents();