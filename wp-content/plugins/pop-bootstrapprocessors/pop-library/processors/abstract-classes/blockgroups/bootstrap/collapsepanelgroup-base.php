<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CollapsePanelGroupBlockGroupsBase extends GD_Template_Processor_PanelBootstrapJavascriptBlockGroupsBase {

	protected function get_block_extension_templates($template_id) {

		$ret = parent::get_block_extension_templates($template_id);
		$ret[] = GD_TEMPLATESOURCE_BLOCKGROUP_COLLAPSEPANELGROUP;
		return $ret;
	}

	function get_dropdown_items($template_id) {

		return array();
	}

	function get_panelactive_class($template_id) {

		return 'in';
	}

	function get_bootstrapcomponent_type($template_id) {

		return 'collapse';
	}
	
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		if ($dropdown_items = $this->get_dropdown_items($template_id)) {
			
			$ret['dropdown-items'] = $dropdown_items;
		}
		
		return $ret;
	}
}