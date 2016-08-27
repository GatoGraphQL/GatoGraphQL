<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_LatestCountsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LATESTCOUNT;
	}

	function get_classes($template_id, $atts) {
	
		return array();
	}

	function get_link_class($template_id, $atts) {
	
		return 'btn btn-link';
	}

	function get_wrapper_class($template_id, $atts) {
	
		return 'text-center alert alert-info alert-sm';
	}

	function get_object_name($template_id, $atts) {
	
		return __('post', 'pop-coreprocessors');
	}

	function get_object_names($template_id, $atts) {
	
		return __('posts', 'pop-coreprocessors');
	}

	function get_title($template_id, $atts) {
	
		return 
			'<i class="fa fa-fw fa-eye"></i>'.
			sprintf(
				__('View %s new %s', 'pop-coreprocessors'),
				'<span class="pop-count">0</span>',
				sprintf(
					'<span class="pop-singular">%s</span><span class="pop-plural">%s</span>',
					$this->get_object_name($template_id, $atts),
					$this->get_object_names($template_id, $atts)
				)
			);
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);	

		$ret[GD_JS_TITLES/*'titles'*/]['link'] = $this->get_title($template_id, $atts);

		return $ret;
	}

	function get_template_runtimeconfiguration($template_id, $atts) {
	
		$ret = parent::get_template_runtimeconfiguration($template_id, $atts);	

		$ret[GD_JS_CLASSES/*'classes'*/]['latestcount'] = implode(' ', $this->get_classes($template_id, $atts));
		$ret[GD_JS_CLASSES/*'classes'*/]['link'] = $this->get_link_class($template_id, $atts);

		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		$this->add_jsmethod($ret, 'loadLatestBlock');

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Initially hidden
		$this->append_att($template_id, $atts, 'class', 'hidden pop-latestcount');

		if ($wrapper_class = $this->get_wrapper_class($template_id, $atts)) {
			$this->append_att($template_id, $atts, 'class', $wrapper_class);
		}
		return parent::init_atts($template_id, $atts);
	}
}