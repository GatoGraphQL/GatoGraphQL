<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CarouselControlsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_CAROUSEL_CONTROLS;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		$this->add_jsmethod($ret, 'carouselControls');
		// $this->add_jsmethod($ret, 'fetchMoreDisable', 'control-left');
		// $this->add_jsmethod($ret, 'fetchMoreDisable', 'control-right');

		return $ret;
	}

	function get_control_class($template_id) {

		return 'carousel-control';
	}
	function get_control_prev_class($template_id) {

		return '';
	}
	function get_control_next_class($template_id) {

		return '';
	}
	function get_title_class($template_id) {

		return '';
	}
	function get_title($template_id) {

		return '';
	}
	protected function get_title_link($template_id) {

		return null;
	}
	protected function get_target($template_id, $atts) {

		return null;
	}
	protected function get_html_tag($template_id, $atts) {

		return 'h4';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['carousel-template'] = $this->get_att($template_id, $atts, 'carousel-template');
		$ret['html-tag'] = $this->get_att($template_id, $atts, 'html-tag');

		if ($control_class = $this->get_control_class($template_id)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['control'] = $control_class;
		}
		if ($control_prev_class = $this->get_control_prev_class($template_id)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['control-prev'] = $control_prev_class;
		}
		if ($control_next_class = $this->get_control_next_class($template_id)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['control-next'] = $control_next_class;
		}
		if ($title = $this->get_title($template_id)) {

			if ($title_class = $this->get_att($template_id, $atts, 'title-class')) {
				$ret[GD_JS_CLASSES/*'classes'*/]['title'] = $title_class;
			}
			if ($target = $this->get_target($template_id, $atts)) {
				$ret['target'] = $target;
			}
		}

		return $ret;
	}

	function get_template_runtimeconfiguration($template_id, $atts) {

		$ret = parent::get_template_runtimeconfiguration($template_id, $atts);

		// Adding in the runtime configuration, because the title/link can change. Eg:
		// the "Latest Thoughts about TPP" in the user profile says "By {{name of org}}"
		if ($title = $this->get_title($template_id)) {
			$ret['title'] = $title;

			// Check if the title_link has been overwritten. If so, use that one. It works
			// only for setting general value, eg: '', and not runtime-specific ones, since
			// it will be cached in pop-cache atts
			$title_link = $this->get_att($template_id, $atts, 'title-link');
			$title_link = isset($title_link) ? $title_link : $this->get_title_link($template_id);
			if ($title_link) {
				$ret['title-link'] = $title_link;
			}
		}

		return $ret;
	}

	function get_template_runtimecrawlableitem($template_id, $atts) {
	
		$ret = parent::get_template_runtimecrawlableitem($template_id, $atts);

		$runtimeconfiguration = $this->get_template_runtimeconfiguration($template_id, $atts);
		if ($title = $runtimeconfiguration['title']) {

			if ($title_link = $runtimeconfiguration['title-link']) {
				$ret[] = sprintf(
					'<a href="%s">%s</a>',
					$title_link,
					$title
				);
			}
			else {
				$ret[] = $title;
			}
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$this->add_att($template_id, $atts, 'title-class', $this->get_title_class($template_id));
		$this->add_att($template_id, $atts, 'html-tag', $this->get_html_tag($template_id, $atts));
		return parent::init_atts($template_id, $atts);
	}
}
