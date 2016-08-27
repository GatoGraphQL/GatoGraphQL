<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENTINNER_MENU_BUTTON', PoP_ServerUtils::get_template_definition('contentinner-menu-button'));
define ('GD_TEMPLATE_CONTENTINNER_MENU_DROPDOWN', PoP_ServerUtils::get_template_definition('contentinner-menu-dropdown'));
define ('GD_TEMPLATE_CONTENTINNER_MENU_INDENT', PoP_ServerUtils::get_template_definition('contentinner-menu-indent'));
define ('GD_TEMPLATE_CONTENTINNER_MENU_SEGMENTEDBUTTON', PoP_ServerUtils::get_template_definition('contentinner-menu-segmentedbutton'));
define ('GD_TEMPLATE_CONTENTINNER_MENU_NAVIGATORSEGMENTEDBUTTON', PoP_ServerUtils::get_template_definition('contentinner-menu-navigatorsegmentedbutton'));
define ('GD_TEMPLATE_CONTENTINNER_MENU_DROPDOWNBUTTON_TOP', PoP_ServerUtils::get_template_definition('contentinner-menu-dropdownbutton-top'));
define ('GD_TEMPLATE_CONTENTINNER_MENU_DROPDOWNBUTTON_SIDE', PoP_ServerUtils::get_template_definition('contentinner-menu-dropdownbutton-side'));
define ('GD_TEMPLATE_CONTENTINNER_MENU_MULTITARGETINDENT', PoP_ServerUtils::get_template_definition('contentinner-menu-multitargetindent'));

class GD_Template_Processor_MenuContentInners extends GD_Template_Processor_ContentSingleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENTINNER_MENU_BUTTON,
			GD_TEMPLATE_CONTENTINNER_MENU_DROPDOWN,
			GD_TEMPLATE_CONTENTINNER_MENU_INDENT,
			GD_TEMPLATE_CONTENTINNER_MENU_SEGMENTEDBUTTON,
			GD_TEMPLATE_CONTENTINNER_MENU_NAVIGATORSEGMENTEDBUTTON,
			GD_TEMPLATE_CONTENTINNER_MENU_DROPDOWNBUTTON_TOP,
			GD_TEMPLATE_CONTENTINNER_MENU_DROPDOWNBUTTON_SIDE,
			GD_TEMPLATE_CONTENTINNER_MENU_MULTITARGETINDENT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTINNER_MENU_BUTTON:
				
				$ret[] = GD_TEMPLATE_LAYOUT_MENU_BUTTON;
				break;

			case GD_TEMPLATE_CONTENTINNER_MENU_DROPDOWN:
				
				$ret[] = GD_TEMPLATE_LAYOUT_MENU_DROPDOWN;
				break;

			case GD_TEMPLATE_CONTENTINNER_MENU_DROPDOWNBUTTON_TOP:
				
				$ret[] = GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_TOP;
				break;

			case GD_TEMPLATE_CONTENTINNER_MENU_DROPDOWNBUTTON_SIDE:
				
				$ret[] = GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_SIDE;
				break;

			case GD_TEMPLATE_CONTENTINNER_MENU_INDENT:
				
				$ret[] = GD_TEMPLATE_LAYOUT_MENU_INDENT;
				break;

			case GD_TEMPLATE_CONTENTINNER_MENU_SEGMENTEDBUTTON:
				
				$ret[] = GD_TEMPLATE_LAYOUT_MENU_SEGMENTEDBUTTON;
				break;

			case GD_TEMPLATE_CONTENTINNER_MENU_NAVIGATORSEGMENTEDBUTTON:

				$ret[] = GD_TEMPLATE_LAYOUT_MENU_NAVIGATORSEGMENTEDBUTTON;
				break;

			case GD_TEMPLATE_CONTENTINNER_MENU_MULTITARGETINDENT:

				$ret[] = GD_TEMPLATE_LAYOUT_MENU_MULTITARGETINDENT;
				break;
		}		

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MenuContentInners();
