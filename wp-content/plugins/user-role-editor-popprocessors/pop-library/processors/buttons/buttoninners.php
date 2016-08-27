<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_BUTTONINNER_EDITMEMBERSHIP', PoP_ServerUtils::get_template_definition('ure-buttoninner-editmembership'));
// define ('GD_URE_TEMPLATE_BUTTONINNER_USERLINK_MEMBERS', 'ure-buttoninner-userlink-members');

class GD_URE_Template_Processor_ButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_BUTTONINNER_EDITMEMBERSHIP,
			// GD_URE_TEMPLATE_BUTTONINNER_USERLINK_MEMBERS,
		);
	}

	function get_fontawesome($template_id, $atts) {
		
		switch ($template_id) {

			case GD_URE_TEMPLATE_BUTTONINNER_EDITMEMBERSHIP:

				return 'fa-fw fa-asterisk';

			// case GD_URE_TEMPLATE_BUTTONINNER_USERLINK_MEMBERS:

			// 	return 'fa-fw ' . gd_navigation_menu_item(POP_URE_POPPROCESSORS_PAGE_MEMBERS, false);
		}

		return parent::get_fontawesome($template_id, $atts);
	}

	function get_btn_title($template_id) {
		
		switch ($template_id) {

			case GD_URE_TEMPLATE_BUTTONINNER_EDITMEMBERSHIP:

				return __('Edit membership', 'ure-popprocessors');
		
			// case GD_URE_TEMPLATE_BUTTONINNER_USERLINK_MEMBERS:

			// 	return __('Members', 'ure-popprocessors');
		}

		return parent::get_btn_title($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_ButtonInners();