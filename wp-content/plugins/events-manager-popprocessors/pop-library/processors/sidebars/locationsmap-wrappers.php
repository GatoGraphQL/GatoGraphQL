<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP', PoP_TemplateIDUtils::get_template_definition('em-layoutwrapper-postlocationsmap'));
define ('GD_TEMPLATE_EM_LAYOUTWRAPPER_USERLOCATIONSMAP', PoP_TemplateIDUtils::get_template_definition('em-layoutwrapper-userlocationsmap'));

class GD_EM_Template_Processor_LocationMapConditionWrappers extends GD_EM_Template_Processor_LocationMapConditionWrappersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP,
			GD_TEMPLATE_EM_LAYOUTWRAPPER_USERLOCATIONSMAP,
		);
	}

	function get_locationlinks_template($template_id) {
	
		switch ($template_id) {
		
			case GD_TEMPLATE_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP:

				return GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS;;

			case GD_TEMPLATE_EM_LAYOUTWRAPPER_USERLOCATIONSMAP:

				return GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS;
		}
		
		return parent::get_locationlinks_template($template_id);
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_LocationMapConditionWrappers();