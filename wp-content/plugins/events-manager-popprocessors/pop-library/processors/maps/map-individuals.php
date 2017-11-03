<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_INDIVIDUAL', PoP_TemplateIDUtils::get_template_definition('em-map-individual'));
define ('GD_TEMPLATE_MAP_SIDEBARINDIVIDUAL', PoP_TemplateIDUtils::get_template_definition('em-map-sidebarindividual'));
define ('GD_TEMPLATE_MAP_INDIVIDUAL_POST', PoP_TemplateIDUtils::get_template_definition('em-map-individual-post'));
define ('GD_TEMPLATE_MAP_INDIVIDUAL_USER', PoP_TemplateIDUtils::get_template_definition('em-map-individual-user'));

class GD_Template_Processor_MapIndividuals extends GD_Template_Processor_MapIndividualsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_INDIVIDUAL,
			GD_TEMPLATE_MAP_SIDEBARINDIVIDUAL,
			GD_TEMPLATE_MAP_INDIVIDUAL_POST,
			GD_TEMPLATE_MAP_INDIVIDUAL_USER,
		);
	}

	function open_onemarker_infowindow($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_MAP_SIDEBARINDIVIDUAL:
				
				return false;
		}

		return parent::open_onemarker_infowindow($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MapIndividuals();