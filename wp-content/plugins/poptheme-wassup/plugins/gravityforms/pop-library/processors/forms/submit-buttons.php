<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_GF_TEMPLATE_SUBMITBUTTON_SENDMESSAGE', PoP_ServerUtils::get_template_definition('gf-submitbutton-sendmessage'));
define ('GD_GF_TEMPLATE_SUBMITBUTTON_SENDEMAIL', PoP_ServerUtils::get_template_definition('gf-submitbutton-sendemail'));
define ('GD_GF_TEMPLATE_SUBMITBUTTON_SUBSCRIBE', PoP_ServerUtils::get_template_definition('gf-submitbutton-subscribe'));

class GD_GF_Template_Processor_SubmitButtons extends GD_Template_Processor_SubmitButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_GF_TEMPLATE_SUBMITBUTTON_SENDMESSAGE,
			GD_GF_TEMPLATE_SUBMITBUTTON_SENDEMAIL,
			GD_GF_TEMPLATE_SUBMITBUTTON_SUBSCRIBE,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_GF_TEMPLATE_SUBMITBUTTON_SENDMESSAGE:

				return __('Send Message', 'poptheme-wassup');

			case GD_GF_TEMPLATE_SUBMITBUTTON_SENDEMAIL:

				return __('Send Email', 'poptheme-wassup');

			case GD_GF_TEMPLATE_SUBMITBUTTON_SUBSCRIBE:

				return __('Subscribe', 'poptheme-wassup');
		}

		return parent::get_label($template_id, $atts);
	}
	
	function get_btn_class($template_id, $atts) {

		switch ($template_id) {

			case GD_GF_TEMPLATE_SUBMITBUTTON_SUBSCRIBE:

				// return 'btn btn-info btn-block';
				return 'btn btn-info';
		}

		return parent::get_btn_class($template_id, $atts);
	}
	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_GF_Template_Processor_SubmitButtons();