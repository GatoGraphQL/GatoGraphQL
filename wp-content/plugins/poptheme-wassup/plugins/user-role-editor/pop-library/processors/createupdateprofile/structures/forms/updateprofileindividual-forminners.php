<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_UPDATE', PoP_TemplateIDUtils::get_template_definition('custom-forminner-profileindividual-update'));

class GD_URE_Custom_Template_Processor_UpdateProfileIndividualFormInners extends GD_URE_Template_Processor_UpdateProfileIndividualFormInnersBase {

	function get_templates_to_process() {
	
		return array(			
			GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_UPDATE,
		);
	}

	function get_layouts($template_id) {
	
		$ret = parent::get_layouts($template_id);
		
		switch ($template_id) {

			case GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_UPDATE:

				// Add common Create/Update components
				GD_Custom_Template_Processor_CreateUpdateProfileIndividualFormsUtils::get_components($template_id, $ret, $this);
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_UPDATE:

				$this->add_att(GD_URE_TEMPLATE_FORMCOMPONENT_INDIVIDUALINTERESTS, $atts, 'load-itemobject-value', true);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_Template_Processor_UpdateProfileIndividualFormInners();