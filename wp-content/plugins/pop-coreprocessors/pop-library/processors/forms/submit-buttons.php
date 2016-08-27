<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SUBMITBUTTON_SUBMIT', PoP_ServerUtils::get_template_definition('submitbutton-submit'));
define ('GD_TEMPLATE_SUBMITBUTTON_OK', PoP_ServerUtils::get_template_definition('submitbutton-ok'));
define ('GD_TEMPLATE_SUBMITBUTTON_SEND', PoP_ServerUtils::get_template_definition('submitbutton-send'));
define ('GD_TEMPLATE_SUBMITBUTTON_SAVE', PoP_ServerUtils::get_template_definition('submitbutton-save'));
define ('GD_TEMPLATE_SUBMITBUTTON_UPDATE', PoP_ServerUtils::get_template_definition('submitbutton-update'));
define ('GD_TEMPLATE_SUBMITBUTTON_SEARCH', PoP_ServerUtils::get_template_definition('submitbutton-search'));

class GD_Template_Processor_SubmitButtons extends GD_Template_Processor_SubmitButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SUBMITBUTTON_SUBMIT,
			GD_TEMPLATE_SUBMITBUTTON_OK,
			GD_TEMPLATE_SUBMITBUTTON_SEND,
			GD_TEMPLATE_SUBMITBUTTON_SAVE,
			GD_TEMPLATE_SUBMITBUTTON_UPDATE,
			GD_TEMPLATE_SUBMITBUTTON_SEARCH,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMITBUTTON_OK:

				return __('OK', 'pop-coreprocessors');

			case GD_TEMPLATE_SUBMITBUTTON_SEND:

				return __('Send', 'pop-coreprocessors');

			case GD_TEMPLATE_SUBMITBUTTON_SAVE:

				return __('Save', 'pop-coreprocessors');

			case GD_TEMPLATE_SUBMITBUTTON_UPDATE:

				return __('Update', 'pop-coreprocessors');

			case GD_TEMPLATE_SUBMITBUTTON_SEARCH:

				return __('Search', 'pop-coreprocessors');
		}

		return parent::get_label($template_id, $atts);
	}

	function get_btn_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMITBUTTON_SEARCH:

				return 'btn btn-info';

			// case GD_TEMPLATE_SUBMITBUTTON_SUBMIT:

			// 	return 'btn btn-primary btn-block';
		}

		return parent::get_btn_class($template_id, $atts);
	}

	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMITBUTTON_SEARCH:

				return 'fa fa-search';
		}

		return parent::get_fontawesome($template_id, $atts);
	}
	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SubmitButtons();