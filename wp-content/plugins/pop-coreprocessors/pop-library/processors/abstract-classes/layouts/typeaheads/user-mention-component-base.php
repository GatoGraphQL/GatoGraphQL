<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserMentionComponentLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUTUSER_MENTION_COMPONENT;
	}
	
	function get_data_fields($template_id, $atts) {
	
		// Important: for Component the size is fixed! It can't be changed from 'avatar-40', because it is hardcoded
		// in layoutuser-mention-component.tmpl
		$avatar_size = GD_AVATAR_SIZE_40;
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);

		// Can't use "user-nicename", because @Mentions plugin does not store the "-" in the html attribute, so it would
		// save the entry as data-usernicename. To avoid conflicts, just remove the "-"
		return array(GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size), 'display-name', 'nicename', 'mention-queryby');
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$avatar_size = GD_AVATAR_SIZE_40;
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);

		$ret['avatar'] = array(
			'name' => $avatar_name,
			'size' => $avatar_size
		);
		
		return $ret;
	}
}
