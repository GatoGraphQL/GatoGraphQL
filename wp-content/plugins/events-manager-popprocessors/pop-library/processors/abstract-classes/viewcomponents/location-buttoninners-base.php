<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_LocationViewComponentButtonInnersBase extends GD_Template_Processor_ButtonInnersBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_VIEWCOMPONENT_LOCATIONBUTTONINNER;
	}

	// function get_location_layout($template_id, $atts) {
	function get_location_layout($template_id) {
		return null;
	}
	function separator($template_id, $atts) {

		return ' | ';
	}

	function get_fontawesome($template_id, $atts) {
		
		return 'fa-map-marker';
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		// if ($location_layout = $this->get_location_layout($template_id, $atts)) {
		if ($location_layout = $this->get_location_layout($template_id)) {

			// $ret['separator'] = $this->get_att($template_id, $atts, 'separator');
			$ret['separator'] = $this->separator($template_id, $atts);
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['location-layout'] = $gd_template_processor_manager->get_processor($location_layout)->get_settings_id($location_layout);
		}
		else {
			$ret[GD_JS_TITLES/*'titles'*/] = array(
				'locations' => __('Locations', 'em-popprocessors')
			);
		}
		
		return $ret;
	}

	// function get_subcomponent_modules($template_id, $atts) {
	function get_subcomponent_modules($template_id) {
	
		// if ($location_layout = $this->get_location_layout($template_id, $atts)) {
		if ($location_layout = $this->get_location_layout($template_id)) {

			return array(
				'locations' => array(
					'modules' => array($location_layout),
					'dataloader' => GD_DATALOADER_LOCATIONLIST
				)
			);
		}

		return parent::get_subcomponent_modules($template_id);
	}
}