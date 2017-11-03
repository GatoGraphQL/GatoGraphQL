<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_FULLUSER_PROFILE', PoP_TemplateIDUtils::get_template_definition('layout-fulluser-profile'));

define ('GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_PROFILE', PoP_TemplateIDUtils::get_template_definition('authorlayout-fulluser-profile'));

define ('GD_TEMPLATE_SINGLELAYOUT_FULLUSER_PROFILE', PoP_TemplateIDUtils::get_template_definition('singlelayout-fulluser-profile'));

class GD_Template_Processor_CustomFullUserLayouts extends GD_Template_Processor_CustomFullUserLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_FULLUSER_PROFILE,
			GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_PROFILE,
			GD_TEMPLATE_SINGLELAYOUT_FULLUSER_PROFILE,
		);
	}

	function get_sidebar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLUSER_PROFILE:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_PROFILE:
			case GD_TEMPLATE_SINGLELAYOUT_FULLUSER_PROFILE:

				$sidebars = array(
					GD_TEMPLATE_LAYOUT_FULLUSER_PROFILE => GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL,
					GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_PROFILE => GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL,
					GD_TEMPLATE_SINGLELAYOUT_FULLUSER_PROFILE => GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL,
				);

				return $sidebars[$template_id];
		}

		return parent::get_sidebar_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomFullUserLayouts();