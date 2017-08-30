<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_AutomatedEmails_Template_Processor_ContentBlocksBase extends GD_Template_Processor_BlocksBase {

	function get_title($template_id) {

		return '';
	}

	protected function show_status($template_id) {

		return false;
	}

	protected function show_disabled_layer($template_id) {

		return false;
	}

	function init_atts($template_id, &$atts) {

		// Convert the classes to styles
		$this->add_general_att($atts, 'convert-classes-to-styles', true);

		return parent::init_atts($template_id, $atts);
	}
}
