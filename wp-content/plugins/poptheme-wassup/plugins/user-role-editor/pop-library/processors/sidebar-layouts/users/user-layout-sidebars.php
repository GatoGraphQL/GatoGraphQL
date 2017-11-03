<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_USERSIDEBAR_VERTICAL_ORGANIZATION', PoP_TemplateIDUtils::get_template_definition('layout-usersidebar-vertical-organization'));
define ('GD_TEMPLATE_LAYOUT_USERSIDEBAR_VERTICAL_INDIVIDUAL', PoP_TemplateIDUtils::get_template_definition('layout-usersidebar-vertical-individual'));

define ('GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_ORGANIZATION', PoP_TemplateIDUtils::get_template_definition('layout-usersidebar-horizontal-organization'));
define ('GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_INDIVIDUAL', PoP_TemplateIDUtils::get_template_definition('layout-usersidebar-horizontal-individual'));

define ('GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION', PoP_TemplateIDUtils::get_template_definition('layout-usersidebar-compacthorizontal-organization'));
define ('GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL', PoP_TemplateIDUtils::get_template_definition('layout-usersidebar-compacthorizontal-individual'));

// class GD_Template_Processor_CustomUserLayoutSidebars extends GD_Template_Processor_LayoutSidebarsBase {
class GD_URE_Template_Processor_CustomUserLayoutSidebars extends GD_Template_Processor_SidebarsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_VERTICAL_ORGANIZATION,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_VERTICAL_INDIVIDUAL,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_ORGANIZATION,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_INDIVIDUAL,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_VERTICAL_ORGANIZATION => GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_VERTICAL_ORGANIZATION,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_VERTICAL_INDIVIDUAL => GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_VERTICAL_INDIVIDUAL,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_ORGANIZATION => GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_ORGANIZATION,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_INDIVIDUAL => GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_INDIVIDUAL,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION => GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_ORGANIZATION,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL => GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_INDIVIDUAL,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_USERSIDEBAR_VERTICAL_ORGANIZATION:
			case GD_TEMPLATE_LAYOUT_USERSIDEBAR_VERTICAL_INDIVIDUAL:

				$this->append_att($template_id, $atts, 'class', 'vertical');
				break;

			case GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_ORGANIZATION:
			case GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_INDIVIDUAL:
			case GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION:
			case GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL:

				$this->append_att($template_id, $atts, 'class', 'horizontal');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomUserLayoutSidebars();