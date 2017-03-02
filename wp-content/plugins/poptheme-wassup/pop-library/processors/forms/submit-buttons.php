<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SUBMITBUTTON_INSTANTANEOUSSEARCH', PoP_ServerUtils::get_template_definition('submitbutton-instantaneoussearch'));

class PoPTheme_Wassup_Template_Processor_SubmitButtons extends GD_Template_Processor_SubmitButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SUBMITBUTTON_INSTANTANEOUSSEARCH,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMITBUTTON_INSTANTANEOUSSEARCH:

				return __('Search', 'poptheme-wassup');
		}

		return parent::get_label($template_id, $atts);
	}

	function get_btn_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMITBUTTON_INSTANTANEOUSSEARCH:

				return 'btn btn-info';
		}

		return parent::get_btn_class($template_id, $atts);
	}

	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMITBUTTON_INSTANTANEOUSSEARCH:

				return 'fa fa-search';
		}

		return parent::get_fontawesome($template_id, $atts);
	}
	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_Template_Processor_SubmitButtons();