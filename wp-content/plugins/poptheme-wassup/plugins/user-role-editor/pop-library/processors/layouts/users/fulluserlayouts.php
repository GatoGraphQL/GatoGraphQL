<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_FULLUSER_ORGANIZATION', PoP_TemplateIDUtils::get_template_definition('layout-fulluser-organization'));
define ('GD_TEMPLATE_LAYOUT_FULLUSER_INDIVIDUAL', PoP_TemplateIDUtils::get_template_definition('layout-fulluser-individual'));
define ('GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_ORGANIZATION', PoP_TemplateIDUtils::get_template_definition('authorlayout-fulluser-organization'));
define ('GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_INDIVIDUAL', PoP_TemplateIDUtils::get_template_definition('authorlayout-fulluser-individual'));
define ('GD_TEMPLATE_SINGLELAYOUT_FULLUSER_ORGANIZATION', PoP_TemplateIDUtils::get_template_definition('singlelayout-fulluser-organization'));
define ('GD_TEMPLATE_SINGLELAYOUT_FULLUSER_INDIVIDUAL', PoP_TemplateIDUtils::get_template_definition('singlelayout-fulluser-individual'));

class GD_URE_Template_Processor_CustomFullUserLayouts extends GD_Template_Processor_CustomFullUserLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_FULLUSER_ORGANIZATION,
			GD_TEMPLATE_LAYOUT_FULLUSER_INDIVIDUAL,
			GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_ORGANIZATION,
			GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_INDIVIDUAL,
			GD_TEMPLATE_SINGLELAYOUT_FULLUSER_ORGANIZATION,
			GD_TEMPLATE_SINGLELAYOUT_FULLUSER_INDIVIDUAL,
		);
	}

	function get_sidebar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLUSER_ORGANIZATION:
			case GD_TEMPLATE_LAYOUT_FULLUSER_INDIVIDUAL:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_ORGANIZATION:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_INDIVIDUAL:
			case GD_TEMPLATE_SINGLELAYOUT_FULLUSER_ORGANIZATION:
			case GD_TEMPLATE_SINGLELAYOUT_FULLUSER_INDIVIDUAL:

				$sidebars = array(
					GD_TEMPLATE_LAYOUT_FULLUSER_ORGANIZATION => GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION,
					GD_TEMPLATE_LAYOUT_FULLUSER_INDIVIDUAL => GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL,
					GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_ORGANIZATION => GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION,
					GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_INDIVIDUAL => GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL,
					GD_TEMPLATE_SINGLELAYOUT_FULLUSER_ORGANIZATION => GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION,
					GD_TEMPLATE_SINGLELAYOUT_FULLUSER_INDIVIDUAL => GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL,
				);

				return $sidebars[$template_id];
		}

		return parent::get_sidebar_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomFullUserLayouts();