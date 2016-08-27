<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTONCONTROL_TYPEAHEADSEARCH', PoP_ServerUtils::get_template_definition('buttoncontrol-typeaheadsearch'));

class GD_Template_Processor_TypeaheadButtonControls extends GD_Template_Processor_ButtonControlsBase {

	function get_templates_to_process() {
	
		return array(
			// GD_TEMPLATE_BUTTONCONTROL_TYPEAHEADDROPDOWN,
			GD_TEMPLATE_BUTTONCONTROL_TYPEAHEADSEARCH,
		);
	}
	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			// case GD_TEMPLATE_BUTTONCONTROL_TYPEAHEADDROPDOWN:
				
			// 	return 'fa-caret-down';

			case GD_TEMPLATE_BUTTONCONTROL_TYPEAHEADSEARCH:

				return 'fa-search';
		}

		return parent::get_fontawesome($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {

			// case GD_TEMPLATE_BUTTONCONTROL_TYPEAHEADDROPDOWN:
			// 	$this->add_jsmethod($ret, 'typeaheadOpen');
			// 	break;

			case GD_TEMPLATE_BUTTONCONTROL_TYPEAHEADSEARCH:
				$this->add_jsmethod($ret, 'typeaheadSearchBtn');
				break;
		}
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TypeaheadButtonControls();