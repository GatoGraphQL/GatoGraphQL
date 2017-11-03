<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_BUTTON_EDITMEMBERSHIP', PoP_TemplateIDUtils::get_template_definition('ure-button-editmembership'));
// define ('GD_URE_TEMPLATE_BUTTON_USERLINK_MEMBERS', 'ure-button-userlink-members');

class GD_URE_Template_Processor_Buttons extends GD_Template_Processor_ButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_BUTTON_EDITMEMBERSHIP,
			// GD_URE_TEMPLATE_BUTTON_USERLINK_MEMBERS,
		);
	}

	function get_buttoninner_template($template_id) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_BUTTON_EDITMEMBERSHIP:

				return GD_URE_TEMPLATE_BUTTONINNER_EDITMEMBERSHIP;

			// case GD_URE_TEMPLATE_BUTTON_USERLINK_MEMBERS:

			// 	return GD_URE_TEMPLATE_BUTTONINNER_USERLINK_MEMBERS;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_url_field($template_id) {
		
		switch ($template_id) {

			case GD_URE_TEMPLATE_BUTTON_EDITMEMBERSHIP:

				return 'edit-membership-url';

			// case GD_URE_TEMPLATE_BUTTON_USERLINK_MEMBERS:

			// 	return 'members-tab-url';
		}

		return parent::get_url_field($template_id);
	}

	function get_linktarget($template_id, $atts) {
		
		switch ($template_id) {

			case GD_URE_TEMPLATE_BUTTON_EDITMEMBERSHIP:

				return GD_URLPARAM_TARGET_ADDONS;
		}

		return parent::get_linktarget($template_id, $atts);
	}

	function get_title($template_id) {
		
		switch ($template_id) {

			case GD_URE_TEMPLATE_BUTTON_EDITMEMBERSHIP:

				return __('Edit membership', 'ure-popprocessors');

			// case GD_URE_TEMPLATE_BUTTON_USERLINK_MEMBERS:

			// 	return __('Members', 'ure-popprocessors');
		}
		
		return parent::get_title($template_id);
	}

	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {

			case GD_URE_TEMPLATE_BUTTON_EDITMEMBERSHIP:

				$ret .= ' btn btn-xs btn-default';
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_Buttons();