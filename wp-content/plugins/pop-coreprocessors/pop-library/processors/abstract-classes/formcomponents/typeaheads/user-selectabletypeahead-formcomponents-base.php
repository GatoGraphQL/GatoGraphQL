<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserSelectableTypeaheadFormComponentsBase extends GD_Template_Processor_SelectableTypeaheadFormComponentsBase {

	function get_avatar_size($template_id, $atts) {

		return GD_AVATAR_SIZE_40;
		// return GD_AVATAR_SIZE_60;
		// return GD_AVATAR_SIZE_TYPEAHEAD_USER;
	}

	function get_dataloader($template_id) {

		// return GD_DATALOADER_PROFILELIST;
		return GD_DATALOADER_USERLIST;
	}

	function get_selected_layout_template($template_id) {

		return GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED;
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;
	
		// Pass same information to its trigger
		$trigger_layout = $this->get_trigger_template($template_id);
		$avatar_size = $this->get_avatar_size($template_id, $atts);
		$this->add_att($trigger_layout, $atts, 'avatar-size', $avatar_size);

		return parent::init_atts($template_id, $atts);
	}

	function get_js_setting($template_id, $atts) {
	
		$ret = parent::get_js_setting($template_id, $atts);

		$avatar_size = $this->get_avatar_size($template_id, $atts);
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);

		$ret['datum-context']['avatar'] = array(
			'name' => $avatar_name,
			'size' => $avatar_size
		);

		return $ret;
	}
}
