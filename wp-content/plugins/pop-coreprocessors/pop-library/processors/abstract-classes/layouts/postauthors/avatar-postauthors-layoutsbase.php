<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostAuthorAvatarLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUTPOST_AUTHORAVATAR;
	}

	function get_url_field($template_id, $atts) {
	
		return 'url';
	}

	function get_avatar_size($template_id, $atts) {
	
		return GD_AVATAR_SIZE_60;
	}

	function get_data_fields($template_id, $atts) {

		$ret = parent::get_data_fields($template_id, $atts);
	
		$url_field = $this->get_url_field($template_id, $atts);
		$avatar_size = $this->get_avatar_size($template_id, $atts);
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);

		return array_merge(
			$ret,
			array(
				$avatar_name, 
				$url_field, 
				'display-name'
			)
		);
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$avatar_size = $this->get_avatar_size($template_id, $atts);
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);

		$ret['avatar'] = array(
			'name' => $avatar_name,
			'size' => $avatar_size
		);
		$ret['url-field'] = $this->get_url_field($template_id, $atts);
		
		return $ret;
	}
}