<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_CODE_MEMBERSLABEL', PoP_TemplateIDUtils::get_template_definition('ure-code-memberslabel'));

class GD_URE_Template_Processor_Codes extends GD_Template_Processor_CodesBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_CODE_MEMBERSLABEL,
		);
	}

	function get_code($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_CODE_MEMBERSLABEL:

				return sprintf(
					'<em>%s</em>',
					__('Members:', 'poptheme-wassup')
				);
		}
	
		return parent::get_code($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_Codes();