<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_MENU_SIDEBAR_ABOUT', PoP_ServerUtils::get_template_definition('block-menu-sidebar-about'));

define ('GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERLOGGEDIN', PoP_ServerUtils::get_template_definition('block-menu-top-userloggedin'));
define ('GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERNOTLOGGEDIN', PoP_ServerUtils::get_template_definition('block-menu-top-usernotloggedin'));
define ('GD_TEMPLATE_BLOCK_MENU_TOPNAV_ABOUT', PoP_ServerUtils::get_template_definition('block-menu-top-about'));
define ('GD_TEMPLATE_BLOCK_MENU_TOP_ADDNEW', PoP_ServerUtils::get_template_definition('block-menu-top-addnew'));

define ('GD_TEMPLATE_BLOCK_MENU_HOME_USERNOTLOGGEDIN', PoP_ServerUtils::get_template_definition('block-menu-home-usernotloggedin'));

define ('GD_TEMPLATE_BLOCK_MENU_SIDE_ADDNEW', PoP_ServerUtils::get_template_definition('block-menu-side-addnew'));
define ('GD_TEMPLATE_BLOCK_MENU_SIDE_SECTIONS', PoP_ServerUtils::get_template_definition('block-menu-side-sections'));
define ('GD_TEMPLATE_BLOCK_MENU_SIDE_SECTIONS_MULTITARGET', PoP_ServerUtils::get_template_definition('block-menu-side-sections-multitarget'));
define ('GD_TEMPLATE_BLOCK_MENU_SIDE_MYSECTIONS', PoP_ServerUtils::get_template_definition('block-menu-side-mysections'));

define ('GD_TEMPLATE_BLOCK_MENU_BODY_ADDCONTENT', PoP_ServerUtils::get_template_definition('block-menu-body-addcontent'));
define ('GD_TEMPLATE_BLOCK_MENU_BODY_SECTIONS', PoP_ServerUtils::get_template_definition('block-menu-body-sections'));
define ('GD_TEMPLATE_BLOCK_MENU_BODY_MYSECTIONS', PoP_ServerUtils::get_template_definition('block-menu-body-mysections'));
define ('GD_TEMPLATE_BLOCK_MENU_BODY_ABOUT', PoP_ServerUtils::get_template_definition('block-menu-body-about'));

class GD_Template_Processor_CustomMenuBlocks extends GD_Template_Processor_MenuBlocksBase {

	// function get_template_url_path() {

	// 	return GD_Template_CustomProcessor::get_custom_template_url_path();
	// }
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_MENU_SIDEBAR_ABOUT,
			GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERLOGGEDIN,
			GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERNOTLOGGEDIN,
			GD_TEMPLATE_BLOCK_MENU_TOPNAV_ABOUT,
			GD_TEMPLATE_BLOCK_MENU_TOP_ADDNEW,
			GD_TEMPLATE_BLOCK_MENU_HOME_USERNOTLOGGEDIN,
			GD_TEMPLATE_BLOCK_MENU_SIDE_ADDNEW,
			GD_TEMPLATE_BLOCK_MENU_SIDE_SECTIONS,
			GD_TEMPLATE_BLOCK_MENU_SIDE_SECTIONS_MULTITARGET,
			GD_TEMPLATE_BLOCK_MENU_SIDE_MYSECTIONS,
			GD_TEMPLATE_BLOCK_MENU_BODY_ADDCONTENT,
			GD_TEMPLATE_BLOCK_MENU_BODY_SECTIONS,
			GD_TEMPLATE_BLOCK_MENU_BODY_MYSECTIONS,
			GD_TEMPLATE_BLOCK_MENU_BODY_ABOUT,
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		$block_inners = array(
			GD_TEMPLATE_BLOCK_MENU_SIDEBAR_ABOUT => GD_TEMPLATE_SIDEBAR_MENU_ABOUT,
			GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERLOGGEDIN => GD_TEMPLATE_INDENTMENU,
			GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERNOTLOGGEDIN => GD_TEMPLATE_INDENTMENU,
			GD_TEMPLATE_BLOCK_MENU_TOPNAV_ABOUT => GD_TEMPLATE_INDENTMENU,
			GD_TEMPLATE_BLOCK_MENU_TOP_ADDNEW => GD_TEMPLATE_INDENTMENU,//GD_TEMPLATE_DROPDOWNBUTTONMENU_TOP,
			GD_TEMPLATE_BLOCK_MENU_HOME_USERNOTLOGGEDIN => GD_TEMPLATE_INDENTMENU,
			GD_TEMPLATE_BLOCK_MENU_SIDE_ADDNEW => GD_TEMPLATE_DROPDOWNBUTTONMENU_SIDE,
			GD_TEMPLATE_BLOCK_MENU_SIDE_SECTIONS => GD_TEMPLATE_INDENTMENU,//GD_TEMPLATE_NAVIGATORSEGMENTEDBUTTONMENU,
			GD_TEMPLATE_BLOCK_MENU_SIDE_SECTIONS_MULTITARGET => GD_TEMPLATE_MULTITARGETINDENTMENU,//GD_TEMPLATE_NAVIGATORSEGMENTEDBUTTONMENU,
			GD_TEMPLATE_BLOCK_MENU_SIDE_MYSECTIONS => GD_TEMPLATE_INDENTMENU,//GD_TEMPLATE_SEGMENTEDBUTTONMENU,
			GD_TEMPLATE_BLOCK_MENU_BODY_ADDCONTENT => GD_TEMPLATE_INDENTMENU,
			GD_TEMPLATE_BLOCK_MENU_BODY_SECTIONS => GD_TEMPLATE_INDENTMENU,
			GD_TEMPLATE_BLOCK_MENU_BODY_MYSECTIONS => GD_TEMPLATE_INDENTMENU,
			GD_TEMPLATE_BLOCK_MENU_BODY_ABOUT => GD_TEMPLATE_INDENTMENU,
		);

		if ($block_inner = $block_inners[$template_id]) {

			$ret[] = $block_inner;
		}

		// Extra blocks
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MENU_HOME_USERNOTLOGGEDIN:
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_INVITENEWUSERS;
				break;
		}

		return $ret;
	}

	protected function get_blocksections_classes($template_id) {

		$ret = parent::get_blocksections_classes($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MENU_SIDEBAR_ABOUT:

				$ret['controlgroup-top'] = 'top';
				break;
		}

		return $ret;
	}

	// protected function get_controlgroup_top($template_id) {

	// 	// Do not add for the quickview, since it is a modal and can't open a new modal (eg: Embed) on top
	// 	$vars = GD_TemplateManager_Utils::get_vars();
	// 	if (!$vars['fetching-json-quickview']) {

	// 		switch ($template_id) {

	// 			case GD_TEMPLATE_BLOCK_MENU_SIDEBAR_ABOUT:

	// 				return GD_TEMPLATE_CONTROLGROUP_TOGGLESIDEINFO_BACK;
	// 		}
	// 	}

	// 	return parent::get_controlgroup_top($template_id);
	// }

	function get_menu($template_id) {

		$menus = array(

			GD_TEMPLATE_BLOCK_MENU_SIDEBAR_ABOUT => GD_MENU_SIDEBAR_ABOUT,

			GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERLOGGEDIN => GD_MENU_TOPNAV_USERLOGGEDIN,
			GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERNOTLOGGEDIN => GD_MENU_TOPNAV_USERNOTLOGGEDIN,
			GD_TEMPLATE_BLOCK_MENU_TOPNAV_ABOUT => GD_MENU_TOPNAV_ABOUT,
			GD_TEMPLATE_BLOCK_MENU_TOP_ADDNEW => GD_MENU_TOPNAV_ADDCONTENT,
			GD_TEMPLATE_BLOCK_MENU_HOME_USERNOTLOGGEDIN => GD_MENU_TOPNAV_USERNOTLOGGEDIN,

			GD_TEMPLATE_BLOCK_MENU_SIDE_ADDNEW => GD_MENU_TOPNAV_ADDCONTENT,
			GD_TEMPLATE_BLOCK_MENU_SIDE_SECTIONS => GD_MENU_SIDENAV_SECTIONS,
			GD_TEMPLATE_BLOCK_MENU_SIDE_SECTIONS_MULTITARGET => GD_MENU_SIDENAV_SECTIONS,
			GD_TEMPLATE_BLOCK_MENU_SIDE_MYSECTIONS => GD_MENU_SIDENAV_MYSECTIONS,

			GD_TEMPLATE_BLOCK_MENU_BODY_ADDCONTENT => GD_MENU_TOPNAV_ADDCONTENT,
			GD_TEMPLATE_BLOCK_MENU_BODY_SECTIONS => GD_MENU_SIDENAV_SECTIONS,
			GD_TEMPLATE_BLOCK_MENU_BODY_MYSECTIONS => GD_MENU_SIDENAV_MYSECTIONS,
			GD_TEMPLATE_BLOCK_MENU_BODY_ABOUT => GD_MENU_TOPNAV_ABOUT,
		);

		return $menus[$template_id];
	}

	
	function init_atts($template_id, &$atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_BLOCK_MENU_TOP_ADDNEW:
			case GD_TEMPLATE_BLOCK_MENU_SIDE_ADDNEW:

				$this->append_att($template_id, $atts, 'class', 'addnew-menu');
				break;

			case GD_TEMPLATE_BLOCK_MENU_HOME_USERNOTLOGGEDIN:

				$this->append_att($template_id, $atts, 'class', 'block-menu-home-usernotloggedin');
				break;
		}
	
		switch ($template_id) {
				
			case GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERLOGGEDIN:
			case GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERNOTLOGGEDIN:
			case GD_TEMPLATE_BLOCK_MENU_TOPNAV_ABOUT:
			case GD_TEMPLATE_BLOCK_MENU_SIDEBAR_ABOUT:
			case GD_TEMPLATE_BLOCK_MENU_TOP_ADDNEW:
			case GD_TEMPLATE_BLOCK_MENU_HOME_USERNOTLOGGEDIN:

				$this->append_att(GD_TEMPLATE_LAYOUT_MENU_INDENT, $atts, 'class', 'nav nav-condensed');
				break;

			// case GD_TEMPLATE_BLOCK_MENU_TOP_ADDNEW:

			// 	$this->add_att(GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_TOP, $atts, 'dropdownmenu-class', 'nav nav-condensed');
			// 	break;
			
			case GD_TEMPLATE_BLOCK_MENU_SIDE_SECTIONS:
			case GD_TEMPLATE_BLOCK_MENU_SIDE_SECTIONS_MULTITARGET:
			case GD_TEMPLATE_BLOCK_MENU_SIDE_MYSECTIONS:

				$this->append_att($template_id, $atts, 'class', 'side-sections-menu');
				break;

			case GD_TEMPLATE_BLOCK_MENU_BODY_ADDCONTENT:
			case GD_TEMPLATE_BLOCK_MENU_BODY_SECTIONS:
			case GD_TEMPLATE_BLOCK_MENU_BODY_MYSECTIONS:
			case GD_TEMPLATE_BLOCK_MENU_BODY_ABOUT:

				$this->append_att($template_id, $atts, 'class', 'side-sections-menu');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomMenuBlocks();