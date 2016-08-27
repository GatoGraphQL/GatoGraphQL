<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEORGANIZATION_UPDATE', PoP_ServerUtils::get_template_definition('custom-forminner-profileorganization-update'));

class GD_URE_Custom_Template_Processor_UpdateProfileOrganizationFormInners extends GD_URE_Template_Processor_UpdateProfileOrganizationFormInnersBase {

	function get_templates_to_process() {
	
		return array(			
			GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEORGANIZATION_UPDATE,
		);
	}

	function get_layouts($template_id) {
	
		$ret = parent::get_layouts($template_id);
		
		switch ($template_id) {

			case GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEORGANIZATION_UPDATE:

				// Add common Create/Update components
				GD_Custom_Template_Processor_CreateUpdateProfileOrganizationFormsUtils::get_components($template_id, $ret, $this);
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEORGANIZATION_UPDATE:

				$this->add_att(GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONTYPES, $atts, 'load-itemobject-value', true);
				$this->add_att(GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONCATEGORIES, $atts, 'load-itemobject-value', true);
				$this->add_att(GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTPERSON, $atts, 'load-itemobject-value', true);
				$this->add_att(GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTNUMBER, $atts, 'load-itemobject-value', true);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_Template_Processor_UpdateProfileOrganizationFormInners();