<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_MENU_SIDEBAR_DOCUMENTATION', PoP_ServerUtils::get_template_definition('block-menu-sidebar-documentation'));
define ('GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION', PoP_ServerUtils::get_template_definition('block-menu-body-documentation'));
define ('GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONARCHITECTURE', PoP_ServerUtils::get_template_definition('block-menu-body-documentation-sectionarchitecture'));
define ('GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONCONTROLLER', PoP_ServerUtils::get_template_definition('block-menu-body-documentation-sectioncontroller'));
define ('GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONMODEL', PoP_ServerUtils::get_template_definition('block-menu-body-documentation-sectionmodel'));
define ('GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONVIEW', PoP_ServerUtils::get_template_definition('block-menu-body-documentation-sectionview'));
define ('GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONAPPLICATIONLOGIC', PoP_ServerUtils::get_template_definition('block-menu-body-documentation-sectionapplicationlogic'));

class GetPoP_Template_Processor_MenuBlocks extends GD_Template_Processor_MenuBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_MENU_SIDEBAR_DOCUMENTATION,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONARCHITECTURE,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONCONTROLLER,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONMODEL,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONVIEW,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONAPPLICATIONLOGIC,
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		$block_inners = array(
			GD_TEMPLATE_BLOCK_MENU_SIDEBAR_DOCUMENTATION => GD_TEMPLATE_SIDEBAR_MENU_ABOUT,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION => GD_TEMPLATE_INDENTMENU,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONARCHITECTURE => GD_TEMPLATE_INDENTMENU,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONCONTROLLER => GD_TEMPLATE_INDENTMENU,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONMODEL => GD_TEMPLATE_INDENTMENU,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONVIEW => GD_TEMPLATE_INDENTMENU,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONAPPLICATIONLOGIC => GD_TEMPLATE_INDENTMENU,
		);

		if ($block_inner = $block_inners[$template_id]) {

			$ret[] = $block_inner;
		}

		return $ret;
	}

	function get_menu($template_id) {

		$menus = array(
			GD_TEMPLATE_BLOCK_MENU_SIDEBAR_DOCUMENTATION => GD_MENU_SIDEBAR_DOCUMENTATION,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION => GD_MENU_SIDEBAR_DOCUMENTATION,//GD_MENU_BODY_DOCUMENTATION,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONARCHITECTURE => GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONARCHITECTURE,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONCONTROLLER => GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONCONTROLLER,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONMODEL => GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONMODEL,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONVIEW => GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONVIEW,
			GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONAPPLICATIONLOGIC => GD_MENU_SIDEBAR_DOCUMENTATION_SECTIONAPPLICATIONLOGIC,
		);

		return $menus[$template_id];
	}

	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_BLOCK_MENU_SIDEBAR_DOCUMENTATION:

				$this->append_att(GD_TEMPLATE_LAYOUT_MENU_INDENT, $atts, 'class', 'nav nav-condensed');
				break;
			
			case GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION:
			case GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONARCHITECTURE:
			case GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONCONTROLLER:
			case GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONMODEL:
			case GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONVIEW:
			case GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION_SECTIONAPPLICATIONLOGIC:
			
				$this->append_att($template_id, $atts, 'class', 'side-sections-menu');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_Template_Processor_MenuBlocks();