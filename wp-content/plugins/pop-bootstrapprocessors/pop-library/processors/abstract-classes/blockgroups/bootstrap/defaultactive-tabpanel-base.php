<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_DefaultActiveTabPanelBlockGroupsBase extends GD_Template_Processor_DefaultActivePanelBootstrapJavascriptBlockGroupsBase {

	// protected function get_block_extension_templates($template_id) {

	// 	$ret = parent::get_block_extension_templates($template_id);
	// 	$ret[] = GD_TEMPLATESOURCE_BLOCKGROUP_TABPANEL;
	// 	return $ret;
	// }
	function get_template_extra_sources($template_id, $atts) {

		$ret = parent::get_template_extra_sources($template_id, $atts);
		$ret['block-extensions'][] = GD_TEMPLATESOURCE_BLOCKGROUP_TABPANEL;
		return $ret;
	}

	function get_panel_header_type($template_id) {

		return 'tab';
	}

	function get_panelactive_class($template_id) {

		return 'active';
	}

	function get_bootstrapcomponent_type($template_id) {

		return 'tabpanel';
	}
	
	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'activeTabLink', 'tablink');
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Through this class, we can identify the blockgroups with tabpanels and place the controlgroup_bottom to the right of the tabs
		$this->append_att($template_id, $atts, 'class', 'blockgroup-tabpanel');
		return parent::init_atts($template_id, $atts);
	}

	

	// function get_block_jsmethod($template_id, $atts) {

	// 	$ret = parent::get_block_jsmethod($template_id, $atts);
	// 	$this->add_jsmethod($ret, 'bootstrapTabPanelInitJSTargets', 'tabpanel');
	// 	return $ret;
	// }
}