<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_USERSIDEBAR_VERTICAL_GENERIC', PoP_ServerUtils::get_template_definition('layout-usersidebar-vertical-generic'));

define ('GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_GENERIC', PoP_ServerUtils::get_template_definition('layout-usersidebar-horizontal-generic'));

define ('GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_GENERIC', PoP_ServerUtils::get_template_definition('layout-usersidebar-compacthorizontal-generic'));

// class GD_Template_Processor_CustomUserLayoutSidebars extends GD_Template_Processor_LayoutSidebarsBase {
class GD_Template_Processor_CustomUserLayoutSidebars extends GD_Template_Processor_SidebarsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_VERTICAL_GENERIC,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_GENERIC,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_GENERIC,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_VERTICAL_GENERIC => GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_VERTICAL_GENERIC,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_GENERIC => GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_GENERIC,
			GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_GENERIC => GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_GENERIC,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_USERSIDEBAR_VERTICAL_GENERIC:

				$this->append_att($template_id, $atts, 'class', 'vertical');
				break;

			case GD_TEMPLATE_LAYOUT_USERSIDEBAR_HORIZONTAL_GENERIC:
			case GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_GENERIC:

				$this->append_att($template_id, $atts, 'class', 'horizontal');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomUserLayoutSidebars();