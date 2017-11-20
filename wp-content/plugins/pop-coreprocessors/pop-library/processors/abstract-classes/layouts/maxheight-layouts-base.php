<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MaxHeightLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_MAXHEIGHT;
	}
	
	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($inners = $this->get_inner_templates($template_id)) {
			$ret = array_merge(
				$ret,
				$inners
			);
		}

		return $ret;
	}

	function get_maxheight($template_id, $atts) {
		
		return null;
	}

	// function get_showmore_btn_html($template_id, $atts) {
		
	// 	$titles = $this->get_showmore_btn_titles($template_id, $atts);
	// 	return sprintf(
	// 		'<div class="dynamicbtn-wrapper"><button class="js-dynamic-show-hide button %1$s" title="%2$s" data-replace-text="%3$s">%2$s</button></div>',
	// 		$this->get_showmore_btn_class($template_id, $atts),
	// 		$titles['more'],
	// 		$titles['less']
	// 	);
	// }

	// function get_showmore_btn_titles($template_id, $atts) {
		
	// 	return array(
	// 		'more' => __('Show more', 'pop-coreprocessors'),
	// 		'less' => __('Show less', 'pop-coreprocessors'),
	// 	);
	// }
	function get_btn_titles($template_id, $atts) {
		
		return array(
			'more' => __('Show more', 'pop-coreprocessors'),
			'less' => __('Show less', 'pop-coreprocessors'),
		);
	}

	function get_btn_class($template_id, $atts) {
		
		return 'btn btn-link';
	}

	function get_inner_templates($template_id) {

		return array();
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		// Integrate with plug-in "Dynamic max height plugin for jQuery"
		if (!is_null($this->get_att($template_id, $atts, 'maxheight'))) {
			
			// This function is critical! Execute immediately, so users can already press on "See more" when scrolling down
			$this->add_jsmethod($ret, 'dynamicMaxHeight', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
		}

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {

		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($inners = $this->get_inner_templates($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['inners'] = $inners;
			foreach ($inners as $inner) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$inner] = $gd_template_processor_manager->get_processor($inner)->get_settings_id($inner);
			}
		}

		if ($btn_titles = $this->get_btn_titles($template_id, $atts)) {
			$ret[GD_JS_TITLES/*'titles'*/] = $btn_titles;
		}
		if ($btn_class = $this->get_btn_class($template_id, $atts)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['btn'] = $btn_class;
		}

		$maxheight = $this->get_att($template_id, $atts, 'maxheight');
		if (!is_null($maxheight)) {
			$ret['maxheight'] = $maxheight;
		}

		// // Integrate with plug-in "Dynamic max height plugin for jQuery"
		// if ($this->get_att($template_id, $atts, 'maxheight')) {
			
		// 	$ret[GD_JS_CLASSES/*'classes'*/]['inner'] = 'dynamic-height-wrap';
		// 	$ret['description-bottom'] = $this->get_showmore_btn_html($template_id, $atts);
		// }

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$this->add_att($template_id, $atts, 'maxheight', $this->get_maxheight($template_id, $atts));
		// $maxheight = $this->get_att($template_id, $atts, 'maxheight');
		// if (!is_null($maxheight)) {

		// 	// Integrate with plug-in "Dynamic max height plugin for jQuery"
		// 	// $this->append_att($template_id, $atts, 'class', 'js-dynamic-height');
		// 	$this->merge_att($template_id, $atts, 'params', array(
		// 		'data-maxheight' => $maxheight,
		// 	));
		// }
		
		return parent::init_atts($template_id, $atts);
	}
}