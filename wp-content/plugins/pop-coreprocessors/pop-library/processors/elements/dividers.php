<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_DIVIDER', PoP_TemplateIDUtils::get_template_definition('divider'));
define ('GD_TEMPLATE_COLLAPSIBLEDIVIDER', PoP_TemplateIDUtils::get_template_definition('collapsible-divider'));

class GD_Template_Processor_Dividers extends GD_Template_Processor_DividersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_DIVIDER,
			GD_TEMPLATE_COLLAPSIBLEDIVIDER,
		);
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_COLLAPSIBLEDIVIDER:
				
				$this->add_att($template_id, $atts, 'class', 'collapse');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_Dividers();