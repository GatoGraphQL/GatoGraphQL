<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ViewComponentButtonsBase extends GD_Template_Processor_ButtonsBase {

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		if ($header = $this->get_header_template($template_id)) {

			$ret[] = $header;

			// if ($header_after = $this->get_header_after_template($template_id)) {

			// 	$ret[] = $header_after;
			// }
		}

		return $ret;
	}

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_VIEWCOMPONENT_BUTTON;
	}

	function get_header_template($template_id) {
		return null;
	}
	function get_header_after_template($template_id) {
		return null;
	}
	function get_url($template_id, $atts) {
		return null;
	}
	
	function get_itemobject_params($template_id) {

		return array();
	}

	function header_show_url($template_id) {

		return false;
	}

	function init_atts($template_id, &$atts) {
	
		if ($itemobject_params = $this->get_itemobject_params($template_id)) {

			$this->merge_att($template_id, $atts, 'itemobject-params', $itemobject_params);
		}

		// Make all viewcomponent buttons invisible
		// $this->append_att($template_id, $atts, 'class', 'pop-hidden-print');
		
		return parent::init_atts($template_id, $atts);
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;
		
		if ($url = $this->get_url($template_id, $atts)) {
			$ret['url'] = $url;
		}

		if ($header = $this->get_header_template($template_id)) {
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['header'] = $gd_template_processor_manager->get_processor($header)->get_settings_id($header);
		}
		
		return $ret;
	}

	function get_template_crawlableitem($template_id, $atts) {

		$ret = parent::get_template_crawlableitem($template_id, $atts);
		
		$configuration = $this->get_template_configuration($template_id, $atts);
	
		if ($url = $configuration['url']) {
			if ($title = $configuration['title']) {
				$ret[] = sprintf(
					'<a href="%s">%s</a>',
					$url,
					$title
				);
			}
		}
		
		return $ret;
	}
}