<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS', PoP_TemplateIDUtils::get_template_definition('viewcomponent-buttonwrapper-postlocations'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS', PoP_TemplateIDUtils::get_template_definition('viewcomponent-buttonwrapper-userlocations'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS', PoP_TemplateIDUtils::get_template_definition('viewcomponent-buttonwrapper-postsidebarlocations'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS', PoP_TemplateIDUtils::get_template_definition('viewcomponent-buttonwrapper-usersidebarlocations'));

class GD_Template_Processor_LocationViewComponentButtonWrapperss extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS;
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USERLOCATIONS;
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS;
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS:

				return 'has-locations';
		}

		return parent::get_condition_field($template_id);
	}

	function get_conditionfailed_layouts($template_id) {

		$ret = parent::get_conditionfailed_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS:

				$ret[] = GD_EM_TEMPLATE_MESSAGE_NOLOCATION;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LocationViewComponentButtonWrapperss();