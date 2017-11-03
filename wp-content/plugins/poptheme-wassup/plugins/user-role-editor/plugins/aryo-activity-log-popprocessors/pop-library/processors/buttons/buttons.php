<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_AAL_TEMPLATE_BUTTON_EDITMEMBERSHIP', PoP_TemplateIDUtils::get_template_definition('ure-aal-button-editmembership'));
define ('GD_URE_AAL_TEMPLATE_BUTTON_VIEWALLMEMBERS', PoP_TemplateIDUtils::get_template_definition('ure-aal-button-viewallmembers'));

class Custom_URE_AAL_PoPProcessors_Template_Processor_Buttons extends GD_Template_Processor_ButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_AAL_TEMPLATE_BUTTON_EDITMEMBERSHIP,
			GD_URE_AAL_TEMPLATE_BUTTON_VIEWALLMEMBERS,
		);
	}

	function get_buttoninner_template($template_id) {

		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_BUTTON_EDITMEMBERSHIP:

				return GD_URE_AAL_TEMPLATE_BUTTONINNER_EDITMEMBERSHIP;

			case GD_URE_AAL_TEMPLATE_BUTTON_VIEWALLMEMBERS:

				return GD_URE_AAL_TEMPLATE_BUTTONINNER_VIEWALLMEMBERS;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_url_field($template_id) {
		
		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_BUTTON_EDITMEMBERSHIP:

				return 'edit-user-membership-url';
		
			case GD_URE_AAL_TEMPLATE_BUTTON_VIEWALLMEMBERS:
		
				return 'community-members-url';
		}

		return parent::get_url_field($template_id);
	}

	function get_title($template_id) {
		
		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_BUTTON_EDITMEMBERSHIP:

				return __('Edit membership', 'poptheme-wassup');
		
			case GD_URE_AAL_TEMPLATE_BUTTON_VIEWALLMEMBERS:

				return __('View all members', 'poptheme-wassup');
		}
		
		return parent::get_title($template_id);
	}

	function get_linktarget($template_id, $atts) {
		
		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_BUTTON_EDITMEMBERSHIP:

				return GD_URLPARAM_TARGET_ADDONS;
		}
		
		return parent::get_linktarget($template_id, $atts);
	}

	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_BUTTON_EDITMEMBERSHIP:
			case GD_URE_AAL_TEMPLATE_BUTTON_VIEWALLMEMBERS:

				$ret .= ' btn btn-xs btn-link';
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Custom_URE_AAL_PoPProcessors_Template_Processor_Buttons();