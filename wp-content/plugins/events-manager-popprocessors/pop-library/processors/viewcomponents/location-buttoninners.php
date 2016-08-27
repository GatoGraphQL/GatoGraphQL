<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS', PoP_ServerUtils::get_template_definition('viewcomponent-buttoninner-locations'));

class GD_Template_Processor_LocationViewComponentButtonInners extends GD_Template_Processor_LocationViewComponentButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS,
			// GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS_PREVIEWDROPDOWN
		);
	}

	// function get_fontawesome($template_id, $atts) {
		
	// 	switch ($template_id) {
					
	// 		case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS:
	// 		// case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS_PREVIEWDROPDOWN:

	// 			return 'fa-map-marker';
	// 	}
		
	// 	return parent::get_fontawesome($template_id, $atts);
	// }

	function get_btn_title($template_id) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS:
			// case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS_PREVIEWDROPDOWN:

				return __('Locations', 'em-popprocessors');
		}
		
		return parent::get_btn_title($template_id);
	}

	// function get_location_layout($template_id, $atts) {
	function get_location_layout($template_id) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS:

				return GD_TEMPLATE_EM_LAYOUT_LOCATIONNAME;
		}
		
		return parent::get_location_layout($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LocationViewComponentButtonInners();