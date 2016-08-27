<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CommentViewComponentHeadersBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_VIEWCOMPONENT_HEADER_COMMENTPOST;
	}

	function get_header_template($template_id) {
	
		return null;
	}

	function get_subcomponent_modules($template_id) {

		if ($header = $this->get_header_template($template_id)) {

			return array(
				'post-id' => array(
					'modules' => array($header),
					'dataloader' => GD_DATALOADER_SECONDCONVERTIBLEPOSTLIST
				)
			);
		}
		
		return parent::get_subcomponent_modules($template_id);
	}

	function header_show_url($template_id, $atts) {

		return false;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		// Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
		if ($this->header_show_url($template_id, $atts)) {

			$ret['header-show-url'] = true;
		}
	
		if ($header = $this->get_header_template($template_id)) {
			
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['header-post'] = $gd_template_processor_manager->get_processor($header)->get_settings_id($header);
		}
		
		return $ret;
	}
}
