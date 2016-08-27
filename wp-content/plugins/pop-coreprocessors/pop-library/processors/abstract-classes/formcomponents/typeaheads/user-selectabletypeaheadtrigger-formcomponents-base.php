<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserSelectableTypeaheadTriggerFormComponentsBase extends GD_Template_Processor_SelectableTypeaheadTriggerFormComponentsBase {

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);
		
		$avatar_size = $this->get_att($template_id, $atts, 'avatar-size');
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);

		$ret['avatar'] = array(
			'name' => $avatar_name,
			'size' => $avatar_size
		);
		
		return $ret;
	}

	function get_selected_template($template_id) {

		return GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED;
	}
}
