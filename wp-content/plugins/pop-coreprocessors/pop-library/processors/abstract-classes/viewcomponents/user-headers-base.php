<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserViewComponentHeadersBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_VIEWCOMPONENT_HEADER_USER;
	}

	function get_avatar_size($template_id, $atts) {
	
		return GD_AVATAR_SIZE_40;
	}

	function get_data_fields($template_id, $atts) {
	
		$avatar_size = $this->get_avatar_size($template_id, $atts);
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);

		$data_fields = array('id', 'display-name', $avatar_name);
		if ($this->header_show_url($template_id, $atts)) {

			$data_fields[] = 'url';
		}

		return $data_fields;
	}

	function header_show_url($template_id, $atts) {

		return false;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
	
		// Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
		if ($this->header_show_url($template_id, $atts)) {

			$ret['header-show-url'] = true;
		}

		$avatar_size = $this->get_avatar_size($template_id, $atts);
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);
		$ret['avatar'] = array(
			'name' => $avatar_name,
			'size' => $avatar_size
		);
		
		return $ret;
	}
}
