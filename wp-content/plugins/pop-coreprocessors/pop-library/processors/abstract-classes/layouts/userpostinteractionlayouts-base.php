<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserPostInteractionLayoutsBase extends GD_Template_ProcessorBase {

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);
		if ($layouts = $this->get_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$layouts
			);
		}
		return $ret;
	}

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_USERPOSTINTERACTION;
	}

	function get_layouts($template_id) {
	
		return array();
	}

	function get_style_class($template_id, $atts) {
	
		return '';
	}

	// function get_notloggedin_img($template_id, $atts) {

	// 	return POP_WPAPI_AVATAR_GENERICUSER;
	// }

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		if ($layouts = $this->get_layouts($template_id)) {

			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['layouts'] = $layouts;
			foreach ($layouts as $layout) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$layout] = $gd_template_processor_manager->get_processor($layout)->get_settings_id($layout);
			}
		}

		// $avatar = get_avatar($this->get_notloggedin_img($template_id, $atts), GD_AVATAR_SIZE_60);
		// $ret['imgs']['notloggedin'] = gd_avatar_extract_url($avatar);

		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		$this->add_jsmethod($ret, 'loadAvatar', 'useravatar');

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// $this->append_att($template_id, $atts, 'class', 'pop-hidden-print');
		$this->append_att($template_id, $atts, 'class', 'frame-addcomment');

		// Add the style for the frame
		if ($classs = $this->get_style_class($template_id, $atts)) {
			$this->append_att($template_id, $atts, 'class', $classs);
		}

		return parent::init_atts($template_id, $atts);
	}
}