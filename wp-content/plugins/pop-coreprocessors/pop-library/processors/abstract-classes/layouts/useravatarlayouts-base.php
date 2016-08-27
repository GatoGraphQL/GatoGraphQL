<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserAvatarLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_USERAVATAR;
	}

	function get_url_field($template_id) {

		return 'url';
	}

	function get_data_fields($template_id, $atts) {

		$avatar_size = $this->get_avatar($template_id);
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);

		$ret = array('display-name', $avatar_name);
		$ret[] = $this->get_url_field($template_id);
		
		return $ret;
	}

	function get_avatar($template_id) {

		// Default value
		return GD_AVATAR_SIZE_60;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$avatar_size = $this->get_avatar($template_id);
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);

		$ret['url-field'] = $this->get_url_field($template_id);
		$ret['avatar'] = array(
			'name' => $avatar_name
		);

		return $ret;
	}
}