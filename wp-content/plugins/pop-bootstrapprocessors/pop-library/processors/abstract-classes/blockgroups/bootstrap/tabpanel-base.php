<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TabPanelBlockGroupsBase extends GD_Template_Processor_PanelBootstrapJavascriptBlockGroupsBase {

	protected function get_block_extension_templates($template_id) {

		$ret = parent::get_block_extension_templates($template_id);
		$ret[] = GD_TEMPLATESOURCE_BLOCKGROUP_TABPANEL;
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
}