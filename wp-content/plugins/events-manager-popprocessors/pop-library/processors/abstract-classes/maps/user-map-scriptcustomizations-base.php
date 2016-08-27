<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserMapScriptCustomizationsBase extends GD_Template_Processor_MapScriptCustomizationsBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MAP_SCRIPTCUSTOMIZATION_USER;
	}

	function get_avatar_marker($template_id) {
	
		return GD_AVATAR_SIZE_40;
	}

	function get_avatar_infowindow($template_id) {
	
		return GD_AVATAR_SIZE_64;
	}

	function get_data_fields($template_id, $atts) {
	
		$avatar_size_sm = $this->get_avatar_marker($template_id);
		$avatar_name_sm = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size_sm);
		$avatar_size_md = $this->get_avatar_infowindow($template_id);
		$avatar_name_md = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size_md);
	
		return array('id', 'display-name', $avatar_name_sm, $avatar_name_md, 'url', 'short-description-formatted');
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$avatar_size_sm = $this->get_avatar_marker($template_id);
		$avatar_name_sm = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size_sm);
		$avatar_size_md = $this->get_avatar_infowindow($template_id);
		$avatar_name_md = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size_md);

		$ret['avatars'] = array(
			'sm' => array(
				'name' => $avatar_name_sm,
				'size' => $avatar_size_sm
			),
			'md' => array(
				'name' => $avatar_name_md,
				'size' => $avatar_size_md
			)
		);
		
		return $ret;
	}
}