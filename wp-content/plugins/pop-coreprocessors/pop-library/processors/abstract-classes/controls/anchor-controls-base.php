<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_AnchorControlsBase extends GD_Template_Processor_ControlsBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_CONTROL_ANCHOR;
	}

	function get_href($template_id, $atts) {
		
		return '#';
	}

	function get_classes($template_id) {

		return array(
			// 'text' => 'hidden-xs pop-btn-title'
			'text' => 'pop-btn-title'
		);
	}

	function get_target($template_id, $atts) {

		return null;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($this->get_att($template_id, $atts, 'make-title')) {
			$ret['make-title'] = true;
		}
		if ($target = $this->get_target($template_id, $atts)) {
			$ret['target'] = $target;
		}

		$ret[GD_JS_CLASSES/*'classes'*/] = $this->get_classes($template_id);

		return $ret;
	}

	function get_template_runtimeconfiguration($template_id, $atts) {

		$ret = parent::get_template_runtimeconfiguration($template_id, $atts);

		// Adding in the runtime configuration, because the link can change. Eg:
		// Organization+Members / Members tabs in the author profile
		if ($href = $this->get_href($template_id, $atts)) {
			$ret['href'] = $href;
		}

		return $ret;
	}

	function get_template_runtimecrawlableitem($template_id, $atts) {
	
		$ret = parent::get_template_runtimecrawlableitem($template_id, $atts);

		$runtimeconfiguration = $this->get_template_runtimeconfiguration($template_id, $atts);
		$configuration = $this->get_template_configuration($template_id, $atts);
		if ($href = $runtimeconfiguration['href']) {

			// Add it only if the href is not pointing to an anchor
			if (substr($href, 0, 1) != '#') {
				$ret[] = sprintf(
					'<a href="%s">%s</a>',
					$href,
					$runtimeconfiguration['text'] ?? $configuration['text'] ?? $configuration['label'] ?? $href
				);
			}
		}
		
		return $ret;
	}
}