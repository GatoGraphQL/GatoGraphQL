<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CONSTANT_FULLUSER_TITLEPOSITION_TOP', 'top');
define ('GD_CONSTANT_FULLUSER_TITLEPOSITION_BODY', 'body');

class GD_Template_Processor_FullUserLayoutsBase extends GD_Template_Processor_FullObjectLayoutsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_FULLUSER;
	}

	function get_data_fields($template_id, $atts) {

		return array_merge(
			parent::get_data_fields($template_id, $atts),
			array('short-description-formatted', 'description-formatted')
		);
	}

	function title_position($template_id, $atts) {

		return GD_CONSTANT_FULLUSER_TITLEPOSITION_TOP;
	}

	function show_description($template_id, $atts) {

		return true;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($this->get_title_template($template_id, $atts)) {
			$ret['title-position'] = $this->title_position($template_id, $atts);
		}

		if ($this->show_description($template_id, $atts)) {
			$ret['show-description'] = true;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		if ($this->show_description($template_id, $atts)) {
			$this->append_att($template_id, $atts, 'class', 'showdescription');
		}
		else {
			$this->append_att($template_id, $atts, 'class', 'nodescription');
		}

		return parent::init_atts($template_id, $atts);
	}
}