<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserTypeaheadComponentLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUTUSER_TYPEAHEAD_COMPONENT;
	}

	function get_data_fields($template_id, $atts) {
	
		// Important: the TYPEAHEAD_COMPONENT should not have data-fields, because it doesn't apply to {{blockSettings.dataset}}
		// but it applies to Twitter Typeahead, which doesn't need these parameters, however these, here, upset the whole get_data_settings
		// To fix this, in GD_TEMPLATE_FORMCOMPONENT_TYPEAHEAD data_settings we stop spreading down, so it never reaches below there to get further data-fields

		// Important: for Component the size is fixed! It can't be changed from 'avatar-40', because it is hardcoded
		// in layoutuser-typeahead-component.tmpl
		$avatar_size = GD_AVATAR_SIZE_40;
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);

		/* FIX THIS: 'url' */
		// is-community needed for the Community filter (it will print a checkbox with msg 'include members?')
		return array(GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size), 'display-name', 'url', 'is-community');
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
