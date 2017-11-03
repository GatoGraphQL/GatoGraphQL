<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

 // Names needed by EM to create the Location
define ('GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONLAT', PoP_TemplateIDUtils::get_template_definition('location_latitude', true));
define ('GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONLNG', PoP_TemplateIDUtils::get_template_definition('location_longitude', true));
define ('GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONNAME', PoP_TemplateIDUtils::get_template_definition('location_name', true));
define ('GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONADDRESS', PoP_TemplateIDUtils::get_template_definition('location_address', true));
define ('GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONTOWN', PoP_TemplateIDUtils::get_template_definition('location_town', true));
define ('GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONSTATE', PoP_TemplateIDUtils::get_template_definition('location_state', true));
define ('GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONPOSTCODE', PoP_TemplateIDUtils::get_template_definition('location_postcode', true));
define ('GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONREGION', PoP_TemplateIDUtils::get_template_definition('location_region', true));

class GD_EM_Template_Processor_CreateLocationTextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONLAT,
			GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONLNG,
			GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONNAME,
			GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONADDRESS,
			GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONTOWN,
			GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONSTATE,
			GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONPOSTCODE,
			GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONREGION
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONNAME:

				return __('Name', 'em-popprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONADDRESS:

				return __('Address', 'em-popprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONTOWN:

				return __('City', 'em-popprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONSTATE:

				return __('State', 'em-popprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONPOSTCODE:

				return __('Post code', 'em-popprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONREGION:

				return __('Region', 'em-popprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONNAME:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONTOWN:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}

	function is_hidden($template_id, $atts) {
	
		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONLAT:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONLNG:
			
				return true;
		}
		
		return parent::is_hidden($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONNAME:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONLAT:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONLNG:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONADDRESS:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONTOWN:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONSTATE:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONPOSTCODE:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONREGION:

				$this->add_att($template_id, $atts, 'pop-form-clear', true);
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONNAME:

				$this->append_att($template_id, $atts, 'class', 'pop-form-clear');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONCOUNTRY:

				$this->append_att($template_id, $atts, 'class', 'address-input');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONLAT:

				$this->append_att($template_id, $atts, 'class', 'pop-form-clear address-lat');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONLNG:
				
				$this->append_att($template_id, $atts, 'class', 'pop-form-clear address-lng');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONADDRESS:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONTOWN:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONSTATE:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONPOSTCODE:
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONREGION:

				$this->append_att($template_id, $atts, 'class', 'pop-form-clear address-input');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CreateLocationTextFormComponentInputs();