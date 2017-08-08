<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CaptchaFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_CAPTCHA;
	}

	// function load_itemobject_value($template_id, $atts) {

	// 	return true;
	// }
	function get_replacestr_from_itemobject($template_id, $atts) {

		$ret = parent::get_replacestr_from_itemobject($template_id, $atts);		

		$replacements = array(
			array(
				'replace-str' => POP_CONSTANT_UNIQUE_ID, 
				'replace-from-feedback' => POP_UNIQUEID
			)
		);
		$ret[] = array(
			'replace-from-field' => 'captcha-imgsrc-original', 
			'replace-where-field' => 'captcha-imgsrc', 
			'replacements' => $replacements,
		);
		$ret[] = array(
			'replace-from-field' => 'session', 
			'replace-where-path' => array('value'), 
			'replace-where-field' => 'session', 
			'replacements' => $replacements,
		);

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Use the label as placeholder
		$this->add_att($template_id, $atts, 'placeholder', $this->get_label($template_id, $atts));

		$session = $this->get_session($template_id, $atts);
		$selected = array('input' => '', 'session' => $session);
		$this->add_att($template_id, $atts, 'selected', $selected);

		return parent::init_atts($template_id, $atts);
	}

	function get_template_path($template_id, $atts) {
	
		return $template_id;
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);
		$options['subnames'] = array('input', 'session');
		return new GD_FormInput_MultipleInputs($options);
	}

	protected function get_session($template_id, $atts) {

		$block_settings_id = $atts['block-settings-id'];
		return $block_settings_id.POP_CONSTANT_UNIQUE_ID;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		// The block settings is is the Captcha session
		// $block_settings_id = $atts['block-settings-id'];
		// $img_src = GD_Captcha::get_image_src($block_settings_id);

		$session = $this->get_session($template_id, $atts);
		$img_src = GD_Captcha::get_image_src($session);

		$ret['session'] = $session;
		$ret['captcha-imgsrc-original'] = $img_src;
		$ret['captcha-imgsrc'] = $img_src;

		if ($placeholder = $this->get_att($template_id, $atts, 'placeholder')) {

			$ret['placeholder'] = $placeholder;
		}

		if ($wrapper_class = $this->get_att($template_id, $atts, 'wrapper-class')) {

			$ret[GD_JS_CLASSES]['wrapper'] = $wrapper_class;
		}
				
		return $ret;
	}
}
