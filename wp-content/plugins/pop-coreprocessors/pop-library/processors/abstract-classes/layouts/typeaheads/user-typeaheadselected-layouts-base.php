<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserTypeaheadSelectedLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUTUSER_TYPEAHEAD_SELECTED;
	}

	function get_extra_templates($template_id) {

		// Allow URE to override adding their own templates to include Community members in the filter 
		return apply_filters('GD_Template_Processor_UserTypeaheadSelectedLayoutsBase:get_extra_templates', array(), $template_id);
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);
		if ($extra_templates = $this->get_extra_templates($template_id)) {
			$ret = array_merge(
				$ret,
				$extra_templates
			);
		}
		return $ret;
	}
	
	function get_data_fields($template_id, $atts) {

		$ret = parent::get_data_fields($template_id, $atts);

		// Important: the TYPEAHEAD_COMPONENT should not have data-fields, because it doesn't apply to {{blockSettings.dataset}}
		// but it applies to Twitter Typeahead, which doesn't need these parameters, however these, here, upset the whole get_data_settings
		// To fix this, in GD_TEMPLATE_FORMCOMPONENT_TYPEAHEAD data_settings we stop spreading down, so it never reaches below there to get further data-fields

		// Important: for Component the size is fixed! It can't be changed from 'avatar-40', because it is hardcoded
		// in layoutuser-typeahead-component.tmpl
		$avatar_size = $this->get_avatar_size($template_id, $atts);
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);

		/* FIX THIS: 'url' */
		// is-community needed for the Community filter (it will print a checkbox with msg 'include members?')
		return array_merge(
			$ret,
			array(GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size), 'display-name', 'url', 'is-community')
		);
	}
	
	function get_avatar_size($template_id, $atts) {

		return GD_AVATAR_SIZE_40;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$avatar_size = $this->get_avatar_size($template_id, $atts);
		$avatar_name = GD_Avatars_Manager_Factory::get_instance()->get_name($avatar_size);

		$ret['avatar'] = array(
			'name' => $avatar_name,
			'size' => $avatar_size
		);

		if ($extras = $this->get_extra_templates($template_id)) {

			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['extras'] = $extras;
			foreach ($extras as $extra) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$extra] = $gd_template_processor_manager->get_processor($extra)->get_settings_id($extra);
			}
		}
		
		return $ret;
	}
}