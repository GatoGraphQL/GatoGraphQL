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

	function get_paneltitle_htmltag($template_id) {

		return 'h3';
	}

	function get_paneltitle_class($template_id) {

		// return 'panel-title collapsepanelgroup-title';
		return 'panel-title';
	}

	function get_outerpanel_class($template_id) {

		return 'panel panel-default';
	}

	function get_panelbody_class($template_id) {

		return 'panel-body';
	}

	function close_parent($template_id) {

		return true;
	}
	
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		if ($dropdown_items = $this->get_dropdown_items($template_id)) {
			
			$ret['dropdown-items'] = $dropdown_items;
		}

		if ($title_htmltag = $this->get_paneltitle_htmltag($template_id)) {
			
			$ret['html-tags']['title'] = $title_htmltag;
		}

		if ($close_parent = $this->close_parent($template_id)) {
			
			$ret['close-parent'] = true;
		}

		if ($body_class = $this->get_panelbody_class($template_id)) {
			
			$ret[GD_JS_CLASSES/*'classes'*/]['body'] = $body_class;
		}

		if ($title_class = $this->get_paneltitle_class($template_id)) {
			
			$ret[GD_JS_CLASSES/*'classes'*/]['title'] = $title_class;
		}

		if ($panel_class = $this->get_outerpanel_class($template_id)) {
			
			$ret[GD_JS_CLASSES/*'classes'*/]['collapsepanel'] = $panel_class;
		}
		
		return $ret;
	}
}