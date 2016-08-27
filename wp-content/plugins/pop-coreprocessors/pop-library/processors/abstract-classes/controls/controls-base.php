<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ControlsBase extends GD_Template_ProcessorBase {

	function get_label($template_id, $atts) {

		return null;
	}
	function get_text($template_id, $atts) {
		
		return $this->get_label($template_id, $atts);
	}
	function get_runtimetext($template_id, $atts) {
		
		return null;
	}
	function get_icon($template_id) {
		
		return null;
	}
	function get_fontawesome($template_id, $atts) {
		
		return null;
	}
	function show_tooltip($template_id, $atts) {

		return false;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);	

		if ($this->get_label($template_id, $atts) && $this->show_tooltip($template_id, $atts)) {
			$this->add_jsmethod($ret, 'tooltip');		
		}

		return $ret;
	}

	function get_template_runtimeconfiguration($template_id, $atts) {

		$ret = parent::get_template_runtimeconfiguration($template_id, $atts);

		// If there is a runtime configuration for the text, it overrides the configuration
		if ($text = $this->get_runtimetext($template_id, $atts)) {
			$ret['text'] = $text;
		}

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($label = $this->get_label($template_id, $atts)) {
			$ret['label'] = $label;
		}
		if ($text = $this->get_text($template_id, $atts)) {
			$ret['text'] = $text;
		}
		if ($icon = $this->get_icon($template_id)) {
			$ret['icon'] = $icon;
		}
		if ($fontawesome = $this->get_fontawesome($template_id, $atts)) {
			$ret[GD_JS_FONTAWESOME/*'fontawesome'*/] = $fontawesome;
		}

		return $ret;
	}
	
	function init_atts($template_id, &$atts) {

		if ($blocktarget = $this->get_att($template_id, $atts, 'block-target')) {

			foreach ($this->get_modules($template_id) as $module) {
					
				$this->add_att($module, $atts, 'block-target', $blocktarget);
			}
		}

		return parent::init_atts($template_id, $atts);
	}
}