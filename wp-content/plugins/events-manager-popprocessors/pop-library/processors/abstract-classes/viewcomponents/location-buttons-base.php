<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_LocationViewComponentButtonsBase extends GD_Template_Processor_ViewComponentButtonsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_VIEWCOMPONENT_LOCATIONBUTTON;
	}

	function get_mapscript_template($template_id) {

		return GD_TEMPLATE_MAP_SCRIPT;
	}

	function get_location_layout($template_id) {

		return GD_TEMPLATE_EM_LAYOUT_LOCATIONNAME;
	}
	function get_location_complement_template($template_id) {

		return null;
	}
	
	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		$ret[] = GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS;
		$ret[] = $this->get_mapscript_template($template_id);
		
		return $ret;
	}

	// function get_subcomponent_modules($template_id, $atts) {
	function get_subcomponent_modules($template_id) {
	
		if ($this->show_each_location($template_id)) {
			
			$modules = array();
			if ($location_layout = $this->get_location_layout($template_id)) {
				$modules[] = $location_layout;
			}
			if ($location_complement = $this->get_location_complement_template($template_id)) {
				$modules[] = $location_complement;
			}

			if ($modules) {
				return array(
					'locations' => array(
						'modules' => $modules,
						'dataloader' => GD_DATALOADER_LOCATIONLIST
					)
				);
			}
		}

		return parent::get_subcomponent_modules($template_id);
	}

	function get_fontawesome($template_id, $atts) {
		
		return 'fa-map-marker';
	}

	function get_url_field($template_id) {

		return 'locationsmap-url';
	}

	function get_url($template_id, $atts) {

		return get_permalink(POP_EM_POPPROCESSORS_PAGE_LOCATIONSMAP);
	}

	function get_linktarget($template_id, $atts) {

		return GD_URLPARAM_TARGET_MODALS;
	}

	function get_link_class($template_id) {
		
		return 'pop-modalmap-link';
	}

	function show_each_location($template_id) {
		
		return true;
	}
	function show_join_locations($template_id) {
		
		return true;
	}
	function get_join_separator($template_id) {
		
		return '<i class="fa fa-fw fa-long-arrow-right"></i>';
	}
	function get_each_separator($template_id) {
		
		return ' | ';
	}
	function get_complement_separator($template_id) {
		
		return ' ';
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$map_script = $this->get_mapscript_template($template_id);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-script'] = $gd_template_processor_manager->get_processor($map_script)->get_settings_id($map_script);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-script-resetmarkers'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS)->get_settings_id(GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS);

		$ret[GD_JS_TITLES/*'titles'*/]['nolocation'] = __('No location', 'em-popprocessors');
		$ret[GD_JS_TITLES/*'titles'*/]['locations'] = $this->get_title($template_id);
		if ($fontawesome = $this->get_fontawesome($template_id, $atts)) {
			$ret[GD_JS_FONTAWESOME/*'fontawesome'*/] = $fontawesome;
		}
		$ret['show-join'] = $this->show_join_locations($template_id);
		$ret['show-each'] = $this->show_each_location($template_id);
		$ret['join-separator'] = $this->get_join_separator($template_id);
		$ret['each-separator'] = $this->get_each_separator($template_id);
		$ret['complement-separator'] = $this->get_complement_separator($template_id);

		if ($this->show_each_location($template_id)) {

			if ($location_layout = $this->get_location_layout($template_id)) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['location-layout'] = $gd_template_processor_manager->get_processor($location_layout)->get_settings_id($location_layout);
			}
			if ($location_complement = $this->get_location_complement_template($template_id)) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['location-complement'] = $gd_template_processor_manager->get_processor($location_complement)->get_settings_id($location_complement);
			}
		}
		
		$ret['locationsfield-name'] = GD_TEMPLATE_FORMCOMPONENT_LOCATIONID.'[]';

		return $ret;
	}
}