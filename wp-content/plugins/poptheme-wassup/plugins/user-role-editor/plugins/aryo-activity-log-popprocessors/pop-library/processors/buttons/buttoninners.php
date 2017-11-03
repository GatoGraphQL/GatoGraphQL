<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_AAL_TEMPLATE_BUTTONINNER_EDITMEMBERSHIP', PoP_TemplateIDUtils::get_template_definition('ure-aal-buttoninner-editmembership'));
define ('GD_URE_AAL_TEMPLATE_BUTTONINNER_VIEWALLMEMBERS', PoP_TemplateIDUtils::get_template_definition('ure-aal-buttoninner-viewallmembers'));

class Custom_URE_AAL_PoPProcessors_Template_Processor_ButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_AAL_TEMPLATE_BUTTONINNER_EDITMEMBERSHIP,
			GD_URE_AAL_TEMPLATE_BUTTONINNER_VIEWALLMEMBERS,
		);
	}

	function get_fontawesome($template_id, $atts) {
		
		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_BUTTONINNER_EDITMEMBERSHIP:

				return 'fa-fw fa-asterisk';

			case GD_URE_AAL_TEMPLATE_BUTTONINNER_VIEWALLMEMBERS:

				return 'fa-fw fa-users';
		}

		return parent::get_fontawesome($template_id, $atts);
	}

	function get_btn_title($template_id) {
		
		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_BUTTONINNER_EDITMEMBERSHIP:

				return __('Edit membership', 'poptheme-wassup');

			case GD_URE_AAL_TEMPLATE_BUTTONINNER_VIEWALLMEMBERS:

				return __('View all members', 'poptheme-wassup');
		}

		return parent::get_btn_title($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Custom_URE_AAL_PoPProcessors_Template_Processor_ButtonInners();