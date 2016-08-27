<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MENU_MULTITARGETINDENT', PoP_ServerUtils::get_template_definition('layout-menu-multitargetindent'));

class GD_Template_Processor_MultiTargetIndentMenuLayouts extends GD_Template_Processor_MultiTargetIndentMenuLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MENU_MULTITARGETINDENT
		);
	}	

	function get_targets($template_id, $atts) {

		$ret = parent::get_targets($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MENU_MULTITARGETINDENT:

				$ret[GD_URLPARAM_TARGET_NAVIGATOR] = '<i class="fa fa-fw fa-angle-right"></i>';
				
				// $icon = '<i class="fa fa-fw fa-angle-right"></i>';
				// $ret[GD_URLPARAM_TARGET_MAIN] = $icon.__('Main', 'pop-coreprocessors');
				// $ret[GD_URLPARAM_TARGET_NAVIGATOR] = $icon.__('Navigator', 'pop-coreprocessors');
				// $ret[GD_URLPARAM_TARGET_ADDONS] = $icon.__('Floating', 'pop-coreprocessors');
				break;
		}
	
		return $ret;
	}

	function get_multitarget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MENU_MULTITARGETINDENT:
				
				// Do not show for mobile phone
				return 'hidden-xs';
		}

		return parent::get_multitarget_class($template_id, $atts);
	}

	function get_multitarget_tooltip($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MENU_MULTITARGETINDENT:
				
				return __('Navigate', 'pop-coreprocessors');
		}

		return parent::get_multitarget_tooltip($template_id, $atts);
	}

	// function get_dropdownmenu_class($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_MENU_MULTITARGETINDENT:
				
	// 			// Do not show for mobile phone
	// 			return 'hidden-xs';
	// 	}

	// 	return parent::get_dropdownmenu_class($template_id, $atts);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MultiTargetIndentMenuLayouts();