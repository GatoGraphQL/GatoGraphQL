<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_AutomatedEmails_Template_Processor_SectionBlocksBase extends GD_Template_Processor_SectionBlocksBase {


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

		// Do not show the filter
		$this->add_att($template_id, $atts, 'show-filter', false);
		
		return parent::init_atts($template_id, $atts);
	}
}
