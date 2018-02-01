<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MapStaticImagesBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MAP_STATICIMAGE;
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);
		
		if ($urlparam = $this->get_urlparam_template($template_id)) {

			$ret[] = $urlparam;
		}
		
		return $ret;
	}

	function get_urlparam_template($template_id) {

		return null;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$ret['url'] = $this->get_staticmap_url();

		if ($urlparam = $this->get_urlparam_template($template_id)) {
	
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['urlparam'] = $gd_template_processor_manager->get_processor($urlparam)->get_settings_id($urlparam);
		}

		return $ret;
	}

	protected function get_staticmap_size() {
		
		return '640x400';
	}

	protected function get_staticmap_type() {
		
		return 'roadmap';
	}

	protected function get_staticmap_center() {
		
		return null;
	}

	protected function get_staticmap_zoom() {
		
		return null;
	}

	protected function get_staticmap_url() {
	
		$url = 'https://maps.googleapis.com/maps/api/staticmap';
		if (POP_COREPROCESSORS_APIKEY_GOOGLEMAPS) {
			$url .= '?key='.POP_COREPROCESSORS_APIKEY_GOOGLEMAPS;
		}

		if ($size = $this->get_staticmap_size()) {
			
			$url = add_query_arg('size', $size, $url);
		}

		if ($type = $this->get_staticmap_type()) {
			
			$url = add_query_arg('maptype', $type, $url);
		}

		if ($center = $this->get_staticmap_center()) {
			
			$url = add_query_arg('center', $center, $url);
		}

		if ($zoom = $this->get_staticmap_zoom()) {
			
			$url = add_query_arg('zoom', $zoom, $url);
		}

		return $url;
	}
}
