<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Do not change the name of this input below!
define ('GD_GF_TEMPLATE_FORMCOMPONENT_FORMID', PoP_TemplateIDUtils::get_template_definition('gform_submit', true));

class GD_GF_Template_Processor_TextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_GF_TEMPLATE_FORMCOMPONENT_FORMID,
		);
	}

	function is_hidden($template_id, $atts) {
	
		switch ($template_id) {
		
			case GD_GF_TEMPLATE_FORMCOMPONENT_FORMID:
			
				return true;
		}
		
		return parent::is_hidden($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_GF_Template_Processor_TextFormComponentInputs();