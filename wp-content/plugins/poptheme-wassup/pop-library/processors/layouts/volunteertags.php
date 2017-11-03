<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTADDITIONAL_VOLUNTEER', PoP_TemplateIDUtils::get_template_definition('layout-postadditional-volunteer'));

class GD_Template_Processor_VolunteerTagLayouts extends GD_Template_Processor_VolunteerTagLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTADDITIONAL_VOLUNTEER
		);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTADDITIONAL_VOLUNTEER:

				$this->append_att($template_id, $atts, 'class', 'label label-warning');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_VolunteerTagLayouts();